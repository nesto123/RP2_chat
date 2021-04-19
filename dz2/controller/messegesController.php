<?php

//require_once __DIR__ . '/../model/service.class.php';


class MessegesController extends BaseController{

    public function index(){

        $ls = new Service();
        //$this->registry->template->errorFlag = False;
        error404();

        if( isset($_POST['channel']) )
            $ls->setCurrentChannal($_POST['channel']);
        $this->registry->template->title = 'Channel: ' . $_SESSION['current_channel']->name;
        $this->registry->template->messegeList = $ls->getMsgFromChannel();
        $this->registry->template->userList = $ls->getUserList();

        $this->registry->template->show( 'messeges_index' );
    }
    
    public function send_messege()
    {
        error404();
        if( isset($_POST['send_messege'] ) )
        {
            $ls = new Service();
            $this->registry->template->messegeList = $ls->sendMessege();
        }
        if( isset($_POST['palac'] ) )
        {
            $ls = new Service();
            $this->registry->template->messegeList = $ls->addThumb();
        }
        $this->index();
    }

    public function MyMesseges()
    {
        error404();
        $ls = new Service();
        //$this->registry->template->errorFlag = False;

        $this->registry->template->title = 'My messeges';
        $this->registry->template->messegeList = $ls->getMyMesseges();
        //print_r($this->registry->template->messegeList);
        
        $this->registry->template->show( 'messeges_my' );
    }

    public function userMesseges()
    {
        error404();
        $ls = new Service();
        //$this->registry->template->errorFlag = False;

        $name = substr($_GET['rt'], strpos($_GET['rt'], '=')+1);
        $this->registry->template->title = 'Messeges from @'. $name;
        $this->registry->template->messegeList = $ls->getMessegesFrom($name);

        $this->registry->template->show( 'messeges_my' );
    }
};


// -------------------------------------------------------
function error404(){
    if( !isset($_SESSION['user']) ){
        header( 'Location: ' . __SITE_URL . '/index.php?rt=404' );
    }
}

function debug()
{
	echo '<pre>$_POST=';
    print_r( $_POST );
    echo '</pre>';
    echo '<pre>$_GET=';
	print_r( $_GET );
        if (session_status() !== PHP_SESSION_NONE) {
            
        echo '$_SESSION=';
        print_r( $_SESSION );
    }
	echo '</pre>';
}

?>