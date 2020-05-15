<?php 

class Channel{

    protected $id, $id_user, $name;

    public function __construct($id, $id_user, $name)
    {
        $this->id = $id;
        $this->id_user = $id_user;
        $this->name = $name;
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