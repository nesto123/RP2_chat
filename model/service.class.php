<?php 

require_once __DIR__ . '/../app/database/db.class.php';
require_once __DIR__ . '/channel.class.php';

class Service{

    public function getMyChannels(){
        
        $channels =[];

        $db = DB::getConnection();
        $st = $db->prepare( 'SELECT * FROM dz2_channels WHERE id_user=:user');
        $st->execute( ['user'=>$_SESSION['user']->id] );

        while( $row = $st->fetch() )
            $channels[] = new Channel($row['id'], $row['id_user'], $row['name']);
        return $channels;
    }

    public function getAllChannels(){
        
        $channels =[];

        $db = DB::getConnection();
        $st = $db->prepare( 'SELECT * FROM dz2_channels');
        $st->execute();

        while( $row = $st->fetch() )
            $channels[] = new Channel($row['id'], $row['id_user'], $row['name']);
        return $channels;
    }

    public function setCurrentChannal( $id)
    {
        $db = DB::getConnection();
        $st = $db->prepare( 'SELECT * FROM dz2_channels WHERE id=:id_');
        $st->execute(['id_'=>$id]);
        $st = $st->fetch();
        $_SESSION['current_channel']= new Channel($st['id'], $st['id_user'], $st['name']);
    }
    
    public function getMsgFromChannel(){
        
        $messege =[];

        $db = DB::getConnection();

        if( isset($_POST['channel']) ){
            $channel_id = $_POST['channel'];
            $this->setCurrentChannal( $channel_id );
        }
        else
            $channel_id = $_SESSION['current_channel']->id;
        $st = $db->prepare( 'SELECT * FROM dz2_messages WHERE id_channel=:id ORDER BY date');
        $st->execute(['id'=>$channel_id]);
        
        while( $row = $st->fetch() ){
            $st2 = $db->prepare( 'SELECT username FROM dz2_users WHERE id=:id_ ');
            $st2->execute(['id_'=>$row['id_user']]);
            $st2 = $st2->fetch();
            $content = editContent($this->getUserList(), $row['content']);
            $messege[] = new Messeges($row['id'], $row['id_user'], $row['id_channel'], $content,$row['thumbs_up'],$row['date'], $st2['username'], '');
        }
        return $messege;
    }

    public function sendMessege()
    {
        $messege = $_POST['poruka'];
        date_default_timezone_set("Europe/Zagreb");
        
        $db = DB::getConnection();
        $st = $db->prepare( 'INSERT INTO dz2_messages (id_user, id_channel, content, thumbs_up, date) VALUES (:val1,:val2,:val3,:val4,:val5)');
        $st->execute(['val1'=> $_SESSION['user']->id, 'val2'=> $_SESSION['current_channel']->id, 'val3'=>$messege, 'val4'=>0, 'val5'=>date("Y-m-d h:i:s")]);
    }

    public function CreateChannel()
    {
        $db = DB::getConnection();
        $st = $db->prepare( 'INSERT INTO dz2_channels (id_user, name) VALUES (:val1,:val2)');
        $st->execute(['val1'=> $_SESSION['user']->id, 'val2'=> $_POST['imeKanala']]);

    }

    public function getMyMesseges(){

        $messege =[];
        $db = DB::getConnection();

        $st = $db->prepare( 'SELECT * FROM dz2_messages WHERE id_user=:id ORDER BY date DESC');
        $st->execute(['id'=>$_SESSION['user']->id]);

        while( $row = $st->fetch() ){
            $content = editContent($this->getUserList(), $row['content']);
            $messege[] = new Messeges($row['id'], $row['id_user'], $row['id_channel'], $content,$row['thumbs_up'],$row['date'], $_SESSION['user']->username, ' ');
        }
        return $messege;
    }
    
    public function addThumb()
    {
        $db = DB::getConnection();

        $st = $db->prepare( 'SELECT thumbs_up FROM dz2_messages WHERE id=:id_');
        $st->execute(['id_'=>$_POST['palac']]);
        $row = $st->fetch();

        $st = $db->prepare( 'UPDATE dz2_messages SET thumbs_up=:val WHERE id=:val2');
        $st->execute(['val'=>($row[0]+1), 'val2'=>$_POST['palac']]);

    }

    public function getUserList()
    {
        $db = DB::getConnection();
        $users = [];

        $st = $db->prepare( 'SELECT username FROM dz2_users');
        $st->execute();

        while( $row = $st->fetch() ){
            $users[] = $row['username'];
        }
        return $users;
    }

    public function getMessegesFrom($name){
        $messege =[];

        $db = DB::getConnection();

        $st2 = $db->prepare( 'SELECT id FROM dz2_users WHERE username=:name_ ');
        $st2->execute(['name_'=>$name]);
        $st2 = $st2->fetch();
        $st2 = $st2[0];

        $st = $db->prepare( 'SELECT * FROM dz2_messages WHERE id_user=:id ORDER BY date DESC');
        $st->execute(['id'=>$st2]);

        while( $row = $st->fetch() ){
            $content = editContent($this->getUserList(), $row['content']);
            $messege[] = new Messeges($row['id'], $row['id_user'], $row['id_channel'], $content,$row['thumbs_up'],$row['date'], $name, ' ');
        }
        return $messege;
    }
};

//  ----------------------------------------


function editContent( $userList, $content){
    foreach( $userList as $user )
        if( strpos($content, '@'.$user) !== False )
            $content = str_replace('@'.$user,'<a href="'. __SITE_URL . '/index.php?rt=messeges/userMesseges/?name='.$user.'">@' . $user . '</a>', $content);
    return $content;
}
function stringToColorCode($str) {
    $code = dechex(crc32($str));
    $code = substr($code, 0, 6);
    return $code;
  }
?>