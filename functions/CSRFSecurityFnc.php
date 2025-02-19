<?php


class CSRFSecure {
    public static function CreateToken() {
        // Generating a unique token and it's expiration time
        $token = bin2hex(random_bytes(32));
        $expiresAt = time() + 60; // Expiration time is set to 1 minute

        $_SESSION["_TOKEN"] = $token;
        $_SESSION["_TOKEN_EXPIRY"] = $expiresAt;

        return $token;
    }

    public static function CreateTokenField() {
    	// Generating a unique token and it's expiration time
        $token = bin2hex(random_bytes(32));
        $expiresAt = time() + 60; // Expiration time is set to 1 minute

        $_SESSION["_TOKEN"] = $token;
        $_SESSION["_TOKEN_EXPIRY"] = $expiresAt;
        
    	echo "<input type='hidden' name='TOKEN' value='" . $token . "' />";
    }

    public static function ValidateToken($token) {
	    if (!isset($_SESSION["_TOKEN"]) || !isset($_POST["TOKEN"])) {
	        return false;
	    }
	    else {
	    	if ($_POST["TOKEN"] == $_SESSION["_TOKEN"]) {
	    		if (time() <= $_SESSION["_TOKEN_EXPIRY"]) {
	    			unset($_SESSION["_TOKEN"]);
	    			unset($_SESSION["_TOKEN_EXPIRY"]);

	    			return true;
	    		}
	    		else {
	    			return false;
	    		}
	    	}
	    	else {
	    		return false;
	    	}
	    }
	}
}

?>