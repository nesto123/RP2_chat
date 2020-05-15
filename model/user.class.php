<?php 

class User{

    protected $id, $username, $password, $email, $registration_sequance, $has_registered;

    public function __construct($id, $username, $password, $email, $registration_sequance, $has_registered)
    {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->registration_sequance = $registration_sequance;
        $this->has_registerd = $has_registered;
    }

    public function __get( $property )
    {
        if( property_exists($this, $property))
            return $this->$property;
    }

    public function __set( $property, $value )
    {
        if( property_exists( $this, $property ) )
            $this->$property = $value;
        return $this;
    }

}

?>