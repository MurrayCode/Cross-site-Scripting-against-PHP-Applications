<?php

/**
 * Cette classe permet de vérifier certains critères d'une série de 
 * variables, en affectant un message d'erreur lorsqu'un problème 
 * est rencontré.
 * 
 * Les paramètres vérifiés sont
 * - occurence minimum (si > 1, alors on attend un array en entrée)
 * - occurence maximum (si > 1, alors on attend un array en entrée)
 * - le type (int, string, alpha, digit, mail). 
 * Il est possible d'en définir par héritage.
 * 
 * Exemple d'utilisation : on vérifie que le mail est présent et 
 * valide, et que le téléphone est valide, s'il est présent. Puis les 
 * méssages d'erreur sont affichés, séparés par un <bR>
 *
 * <pre>
 * $val = new var_validator();
 * $val->validate($_GET['email'], 1, 1, "mail", 
 * 		"Merci de vérifier votre adresse Email");
 * $val->validate($_GET['tel'], 0, 1, "digit", 
 *		"Merci de vérifier le téléphone");
 * if(!$val->ok)
 * 		$val->echo_error_msg("<br/>");
 * </pre>
 * This file is part of the Brim project.
 * The brim-project is located at the following 
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 * 
 * <pre> Enjoy :-) </pre>
 *
 * @author Michael Haussmann - review 11/11/2003
 * @package org.brim-project.framework
 * @subpackage util.request
 *
 * @copyright Michael Haussmann
 *
 * @license http://opensource.org/licenses/gpl-license.php 
 * The GNU Public License
 */
class VariableValidator {

	/**
	 * @var $error_msg : array de string : messages d'erreur
	 * @desc $error_msg : array de string : messages d'erreur
	 */
	var $error_msg;
	
	/**
	 * @var $ok : boolean : présence ou absence d'erreur. (true = pas d'erreur)
	 */
	var $ok;
	
	// membres privés
	var $default_max;
	var $default_type;
	var $default_error_msg;

	/**
	 * Constructor
	 */
	function VariableValidator()
	{ 
		settype($this->error_msg, "array");
		$this->ok = TRUE;
	}

	/**
	 * Cette fonction valide la variable $var
	 */
	function validate($var, $min, $max, $type, $error_msg)
	{

		// Si $var n'est pas définie et qu'elle n'a pas besoin de 
		// l'être il n'y a pas d'erreur
		if(((!isset($var))||($var == ""))&&($min == 0))
		{
   			return;
		}
		// Vérification min-max
		if(!$this->check_min_max($var, $min, $max))
		{
			$this->set_error($error_msg);
			return;
		}
		// vérification du type :
		// choix de la fonction correspondente
		$type_function = "check_type_".$type;
		// si la variable est un champs on boucle pour vérifier chaque element
		if(is_array($var))
		{
			$i = 0;
			while($var[$i])
			{
				if(!$this->$type_function($var[$i]))
				{
					$this->set_error($error_msg);
					return;
				}
				$i++;
			}
			// sinon nous vérifions $var une fois
		}
		else
		{
			if(!$this->$type_function($var))
			{
				$this->set_error($error_msg);
				return;
			}
		}
		// à défaut d'erreur, on retourne
		return;
	}

	function set_error($error_msg)
	{
		$this->ok = FALSE;
		if((!in_array($error_msg,$this->error_msg)))
		{ 
			// pour ne pas répéter un même message d'erreur.
			$this->error_msg[] = $error_msg;
		}
	}

	/**
 	 * Cette fonction vérifie si la réponse $var répond aux 
	 * exigeances en terme de nombre minimal et maximal de réponses.
 	 */
	function check_min_max($var, $min, $max)
	{
		if($min > $max)
		{ 
			// à moins que l'on décide que $max à 0 -> infinie?
			echo "Erreur fatale de config dans min max pour";
			var_dump($var);
			exit;
		}
		if((!isset($var))||($var == ""))
		{
			$field = 0;
		}
		elseif (!is_array($var))
		{
			$field = 1;
		}
		else
		{
			$field = count($var);
		}
		return (($min <= $field)&&($field <= $max));
	}

	/**
	* @return void
	* @param $separator string separator
	* @desc Echoes error messages separated by $separator       
	*/
	function echo_error_msg($separator)
	{
		$i = 0;
		while($this->error_msg[$i])
		{
			echo $this->error_msg[$i];
			echo $separator;
			$i++;
		}
	}

	/**
 	 * @return void
	 * @param $separator string separator
	 * @desc returns array of error messages
	 */
	function get_error_msg()
	{
		if(empty($this->error_msg)) 
		{
			return array();
		}
		return $this->error_msg;
	}

	function set_default($min, $max, $type, $error_msg)
	{
		$this->default_min = $min;
		$this->default_max = $max;
		$this->default_type = $type;
		$this->default_error_msg = $error_msg;
	}

	function validate_default($variable_argument_number)
	{
		$num_args = func_num_args();
		for ($index = 0; $index < $num_args; ++$index)
		{
			$var = func_get_arg($index);
			$this->validate($var, 
				$this->default_min, 
				$this->default_max, 
				$this->default_type, 
				$this->default_error_msg);
		}
	}

	function check_type_int($var)
	{ 
		// this may not work because all the GET or POST values are strings.
		return (is_int($var));
	}

	function check_type_string($var)
	{
		return (is_string($var));
	}

	function check_type_mail($var)
	{
		$email_regexp = "^([-!#\$%&'*+./0-9=?A-Z^_`a-z{|}~ ])+@([-!#\$%&'*+/0-9=?A-Z^_`a-z{|}~ ]+\\.)+[a-zA-Z]{2,4}\$";
		return(eregi($email_regexp,$var)!=0);
	}

      function check_type_digit($var)
      {
               return(eregi("^[[:digit:]]+$",$var)!=0);
      }

      function check_type_alpha($var)
      {
               return(eregi("^[[:alpha:]]+$",$var)!=0);
      }

}



// Il est possible de créer ses propres types en ajoutant des classes enfants.
// Il faut néanmoins veiller à appeler le constructeur du parent.
// Si ce dernier n'est pas appelé $this->ok sera NULL et non TRUE par défaut.

/*
class my_validation extends VariableValidator {

      function my_validation(){
            parent::var_validator();
      }

      function check_type_blob($var){
               ($var == "blob") ? ($return = TRUE) : ($return = FALSE);
               return $return;
      }
}
*/
/*
En plus on pourrait tout vérifier qui vient par GET ou POST:
(mais est-ce bien utile)?

$val->validate_all("get", 1, 1, "string", "Vérifier vos entrées"); // En paramètre est envoyé la méthode
*/

?>
