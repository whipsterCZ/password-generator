<?php

final class PasswordGenerator {

	const SPECIAL_LETTERS= "ghjklmnopqrstuvwxyz";
	const SPECIAL_CHARS = "%~=#-+@:$";
	const PASSWORD_LENGTH = 16;

	/**
	 * pro extra differenciaci algorytmu upravte
	 * @var int
	 */
	const SEED = 4;  //1-10

    /**
     * @phpVersion 5.6>
     *  Vygeneruje bezpečnéa unikátné heslo pro konkrétní službu
     *  + stačí si pamatovat jediné heslo (secret)
     *  + hash který se používá je rozdělen podle klíče, který je secret ...
     *  + ořezává neviditelné znaky ve službě a secretu
     *
     * @param string $service  {název domény, služby nebo souboru}   examples - google, eos, vault apod..   bude rovněž součástí vygenrováného hesla...
     * @param string $secret - toto je skutečné heslo které si musíš pamatovat - platí pro něj stejná pravidla jako pro normální heslo, alespon 8 znaků, nemělo by to být jméno, adresa psč apod..  ideálně by mělo obsahovat speciální znak..
     *                v případě že zůstane algorytmus secret, tak ani salt nemusí být složitý ani nemusí obsahovat speciální znaky :)
     * @param int|string $version - nepovinné | default(1) - kdyby bylo heslo odhaleno, možno vytvořit další heslo...
     * @return string Heslo - 16 znaků, speciální znaky, velké písmeno a číslice
     */
    public function generate($service, $secret, $version = 1){
        $service = $this->sanitizeService($service);

	    $root = trim($secret) . "@". strrev($service) . ":". $version;
	    $hash =  sha1($root);

	    $requiredChars = "";
	    $digits = $this->getDigits($hash, $this->getDigits($root,"69"));
	    $letters = $this->getLetters($hash, $this->getLetters($root, "dk") );
	    $seed1 = $this->getSeed($digits);
	    $seed2 = self::SEED;

	    if(0 == ($seed1 % 3)) {			
  			$requiredChars .= strtoupper($this->char($letters,1));
        $requiredChars .= $this->char($letters,0);
		    $requiredChars .= $this->char($digits,0);
	    } else if(1 == ($seed1 % 3)) {
		    $requiredChars .= $this->char($letters,1);
	      $requiredChars .= strtoupper($this->char($letters,0));
		    $requiredChars .= $this->char($digits,0);
		  } else {
		    $requiredChars .= $this->char($digits,0);
		    $requiredChars .= $this->char($letters,1);
		    $requiredChars .= strtoupper($this->char($letters,0));
	    }
	    $requiredChars .= $this->char(self::SPECIAL_CHARS, $seed1);

	    $password = substr($hash,0, self::PASSWORD_LENGTH -strlen($requiredChars) );
	    $password = $this->replaceChar($password, strtoupper($this->char($password,$seed2*($seed1%8) )), $seed2*($seed1%8) );
	    $password = $this->replaceChar($password, strtoupper($this->char($password,$seed1%6)), $seed1%6 );
	    $password = $this->replaceChar($password, $this->char(self::SPECIAL_CHARS,-$seed2*$seed1), $seed1+1 );
	    $password = $this->replaceChar($password, strtoupper($this->char(self::SPECIAL_LETTERS,$seed1%4 )) , 10+$seed1%20 );

	    $password = $requiredChars.$password;
	    return $password;
    }

    /**
     * @param $service
     * @return string
     */
    private function sanitizeService($service){
        $service = strtolower( $service );
        $service = preg_replace("/[^a-zA-Z0-9]+/", "", $service);
        $service = trim($service);
        return $service;
    }

	/**
	 * získá seed pro výběr data z hashe
	 * @param string $number
	 * @return int
	 */
    protected function getSeed($number){
	    $sum = 0;
	    $digits = str_split($number);
	    foreach ($digits as $digit) {
		    $sum += intval($digit);
	    }
	    return $sum;
    }

	/**
	 * vrátí písmenko
	 * @param string $chars
	 * @param int $index
	 * @return string
	 */
    private function char($chars, $index) {
    	$lastIndex = strlen($chars) - 1;
    	$index = abs($index % $lastIndex);
	    $char = $chars[$index];
	    return $char;
    }

	/**
	 * přepíše písmenko
	 * @param string $chars
	 * @param string $char
	 * @param int $index
	 * @return string
	 */
	private function replaceChar($string, $char, $index) {
		$lastIndex = strlen($string) - 1;
		$index = abs($index % $lastIndex);
		$string[$index] = $char;
		return $string;
	}

	/**
	 * @param string $hash
	 * @param string $default
	 * @return string
	 */
    private function getDigits($hash , $default = ""){
		$number = preg_replace("/[^0-9]/","",$hash);
		if(!empty($number)) {
			return $number;
		}
		return $default;
    }

	/**
	 * @param string $hash
	 * @param string $defaults
	 * @return string
	 */
    private function getLetters($hash, $defaults = ""){
		$chars = array();
		preg_match_all("/[a-z]/",$hash,$chars);
		if(isset($chars[0]) && count($chars[0])){
			return implode("",$chars[0]);
		}
		return $defaults;
    }

}

