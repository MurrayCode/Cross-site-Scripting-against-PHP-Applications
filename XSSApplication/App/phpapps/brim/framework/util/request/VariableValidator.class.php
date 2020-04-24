<?php

/**
 * Cette classe permet de v�rifier certains crit�res d'une s�rie de 
 * variables, en affectant un message d'erreur lorsqu'un probl�me 
 * est rencontr�.
 * 
 * Les param�tres v�rifi�s sont
 * - occurence minimum (si > 1, alors on attend un array en entr�e)
 * - occurence maximum (si > 1, alors on attend un array en entr�e)
 * - le type (int, string, alpha, digit, mail). 
 * Il est possible d'en d�finir par h�ritage.
 * 
 * Exemple d'utilisation : on v�rifie que le mail est pr�sent et 
 * valide, et que le t�l�phone est valide, s'il est pr�sent. Puis les 
 * m�ssages d'erreur sont affich�s, s�par�s par un <bR>
 *
 * <pre>
 * $val = new var_validator();
 * $val->validate($_GET['email'], 1, 1, "mail", 
 * 		"Merci de v�rifier votre adresse Email");
 * $val->validate($_GET['tel'], 0, 1, "digit", 
 *		"Merci de v�rifier le t�l�phone");
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
	 * @var $ok : boolean : pr�sence ou absence d'erreur. (true = pas d'erreur)
	 */
	var $ok;
	
	// membres priv�s
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

		// Si $var n'est pas d�finie et qu'elle n'a pas besoin de 
		// l'�tre il n'y a pas d'erreur
		if(((!isset($var))||($var == ""))&&($min == 0))
		{
   			return;
		}
		// V�rification min-max
		if(!$this->check_min_max($var, $min, $max))
		{
			$this->set_error($error_msg);
			return;
		}
		// v�rification du type :
		// choix de la fonction correspondente
		$type_function = "check_type_".$type;
		// si la variable est un champs on boucle pour v�rifier chaque element
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
			// sinon nous v�rifions $var une fois
		}
		else
		{
			if(!$this->$type_function($var))
			{
				$this->set_error($error_msg);
				return;
			}
		}
		// � d�faut d'erreur, on retourne
		return;
	}

	function set_error($error_msg)
	{
		$this->ok = FALSE;
		if((!in_array($error_msg,$this->error_msg)))
		{ 
			// pour ne pas r�p�ter un m�me message d'erreur.
			$this->error_msg[] = $error_msg;
		}
	}

	/**
 	 * Cette fonction v�rifie si la r�ponse $var r�pond aux 
	 * exigeances en terme de nombre minimal et maximal de r�ponses.
 	 */
	function check_min_max($var, $min, $max)
	{
		if($min > $max)
		{ 
			// � moins que l'on d�cide que $max � 0 -> infinie?
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



// Il est possible de cr�er ses propres types en ajoutant des classes enfants.
// Il faut n�anmoins veiller � appeler le constructeur du parent.
// Si ce dernier n'est pas appel� $this->ok sera NULL et non TRUE par d�faut.

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
En plus on pourrait tout v�rifier qui vient par GET ou POST:
(mais est-ce bien utile)?

$val->validate_all("get", 1, 1, "string", "V�rifier vos entr�es"); // En param�tre est envoy� la m�thode
*/

?>
