<?php
    function utf8_to_unicode ($str)
	{
		$result = '';
        $values = array();
        $lookingFor = 1;
        for ($i = 0; $i < strlen ($str); $i++ )
		{
            $thisValue = ord ($str [$i]);
            if ($thisValue < 128)
			{
				$result .= chr ($thisValue);
			}
            else
			{
                if (count ($values) == 0)
				{
					$lookingFor = ($thisValue < 224) ? 2 : 3;
               	}
				//echo '*'.$str[$i].'*'.$thisValue.'*'.count($values).'*'.$lookingFor;
                $values [] = $thisValue;
                if (count ($values) == $lookingFor)
				{
                    $number = ($lookingFor == 3) ?
                        (($values[0] % 16 ) * 4096) + (($values[1] % 64) * 64) + ($values[2] % 64):
                    	(($values[0] % 32 ) * 64) + ($values[1] % 64);
					if ($number > 127)
					{
						$result .= '&#'.$number.';';
					}
					else
					{
						$result .= chr ($number);
					}
                    $values = array();
                    $lookingFor = 1;
                }
				else
				{
					$result .= '&#'.$thisValue.';';
				}
            }
        }
		return $result;
    }


	$file = 'dictionary_zh_TW.php';
	if (!file_exists ($file))
	{
		die ('File '.$file.' does not exist');
	}
	$fp = fopen ($file, 'r');
	if ($fp == null)
	{
		die ('Error opening file '.$file);
	}

	$result = '';
	while (!feof ($fp))
	{
		$line = fgets ($fp, 4096);
		$result .= utf8_to_unicode ($line);
	}
	fclose ($fp);
	echo $result;
?>