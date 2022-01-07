<?php


namespace CycleSpaceInvaders\Controllers;

class PageController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }


    public function what()
    {
        echo $this->tpl->render('what');
    }


    public function getInvolved()
    {
        $rcpg_ids = array(1971268730, 3095701085, 2330741400, 209496062, 836395631337943041, 570213090, 312784768, 53996964, 1054079618431569923, 127925602, 1106908585555042304, 954808357591900160);

        $sql = "SELECT * FROM `" . $_ENV['DB_USER_TABLE'] . "` WHERE `id` IN (" . implode(',', $rcpg_ids) . ")";

        $stmt = $this->dbconn->prepare($sql);

        $stmt->execute();

        $rcpgs = $stmt->fetchAll();

        echo $this->tpl->render('get-involved', ['rcpgs' => $rcpgs]);
    }

    public function credit()
    {
        echo $this->tpl->render('get-involved');
    }
}
