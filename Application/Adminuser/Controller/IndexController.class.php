<?php
namespace Adminuser\Controller;
use Think\Controller;
use Common\Common;

class IndexController extends Controller {

    public function index() {
        redirect('/index.html');
    }
}
