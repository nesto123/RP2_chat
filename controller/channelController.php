<?php

//require_once __DIR__ . '/../model/service.class.php';


class ChannelController extends BaseController{

    public function index(){
        $ls = new Service();
        //$this->registry->template->errorFlag = False;

        error404();

        $this->registry->template->title = $_SESSION['tab'] = 'My Channels';
        $this->registry->template->channelList = $ls->getMyChannels();
        
        $this->registry->template->show( 'channel_index' );
        //require_once __DIR__ . '/../view/channel_index.php';
    }

    public function AllChannels(){

        $ls = new Service();
        //$this->registry->template->errorFlag = False;

        error404();

        $this->registry->template->title = $_SESSION['tab'] = 'All Channels';
        $this->registry->template->channelList = $ls->getAllChannels();
        
        $this->registry->template->show( 'channel_index' );
    }

    public function newInit()       //  Create new channel
    {
        //$this->registry->template->errorFlag = False;
        $ls = new Service();
        error404();
        $this->registry->template->title = $_SESSION['tab'] = 'Create new channel';
        $this->registry->template->show( 'channel_startnew' );

    }


    public function startNew()
    {
        $ls = new Service();

        error404();
        //$this->registry->template->errorFlag = False;
        $this->registry->template->title = $_SESSION['tab'] = 'Create new channel';
        
        if( isset( $_POST['imeKanala']) ){
            $ls->CreateChannel();
			$this->registry->template->errorFlag = True;
			$this->registry->template->errorMsg = 'Kreiran novi kanal '.$_POST['imeKanala'];

        }

        $this->registry->template->show( 'channel_startnew' );
    }
}

//  -----------------
function error404(){
    if( !isset($_SESSION['user']) ){
        header( 'Location: ' . __SITE_URL . '/index.php?rt=404' );
    }
}


function debug()
{
	echo '<pre>$_POST=';
	print_r( $_POST );
	if (session_status() !== PHP_SESSION_NONE) {
		
	echo '$_SESSION=';
	print_r( $_SESSION );
    }
	echo '</pre>';
}


?>