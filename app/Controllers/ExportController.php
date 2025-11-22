<?php

namespace CycleSpaceInvaders\Controllers;

use PDO;

class ExportController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function foo(){





        $sql = "SELECT id
                FROM " . $_ENV['DB_TWEET_TABLE'] . "
                WHERE hashtags LIKE '%freethecyclelanes%'
                ORDER BY created_at ASC";

        $stmt = $this->dbconn->prepare($sql);

        $stmt->execute();

        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

//    echo count($res);

    foreach($res as $r){
        echo $r['id'].",\n";
    }
        
        
        
    }

    public function Player($username)
    {

        // strip off @
        $username = str_replace('@', '', $username);

        //TODO test for valid username

        // get user
        $sqluser = "select * from " . $_ENV['DB_USER_TABLE'] . " WHERE `username` = :username  LIMIT 1";

        $stmt = $this->dbconn->prepare($sqluser);

        $stmt->execute([":username" => $username]);

        $user = $stmt->fetch();

        if (!$user) {

            // Render a template
            echo $this->tpl->render('errors::404_user', ['username' => $username]);

            return false;
        }

        $sqlcount = "select count(*) as total_records, sum(score) as total_score from " . $_ENV['DB_TWEET_TABLE'] . " WHERE `user_id` = :user_id";

        $stmt = $this->dbconn->prepare($sqlcount);
        $stmt->execute([":user_id" => $user['id']]);
        $row = $stmt->fetch();
        $total_records = $row['total_records'];

        //$total_pages=ceil($total_records/$this->ppage);

        //  $offset=($page-1)*$this->ppage;

        /* End Paging Info */

        $score = $row['total_score'];


        $this->dbconn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        $sql = "SELECT * FROM `tweets`
              WHERE `user_id` = :user_id
              ORDER BY created_at DESC";

        $stmt = $this->dbconn->prepare($sql);

        $stmt->execute([":user_id" => $user['id']]);

        try {
            $stmt->execute();
        } catch (Exception $e) {

            //var_dump($stmt->debugDumpParams());
        }

        $tweets = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // get last active date
        $sqluser = "select created_at from " . $_ENV['DB_TWEET_TABLE'] . "
                  WHERE `user_id` = :id
                  ORDER BY created_at DESC
                  LIMIT 1";

        $stmt = $this->dbconn->prepare($sqluser);

        $stmt->execute([":id" => $user['id']]);

        $res = $stmt->fetch();

        $last_active = $res['created_at'];

        // get user INFO
        //         $sql="SELECT * FROM `users` WHERE `id` = :id LIMIT 1" ;
        //
        //
        //         $stmt  = $this->dbconn->prepare($sql);
        //
        //         $stmt->execute([ ":id" => $id]);
        //
        // try{
        //
        //         $stmt->execute();
        //
        //       }catch(Exception $e){
        //
        //         //var_dump($stmt->debugDumpParams());
        //
        //         }
        //
        //         $user = $stmt ->fetch();


        //for sparkline
        //   $sql="SELECT COUNT(`tweet`), EXTRACT(YEAR_MONTH FROM `timestamp`) as `month`
        // FROM `ftcl`
        // WHERE `user_id` = :user_id
        // GROUP BY EXTRACT(YEAR_MONTH FROM `timestamp`)";
        //
        //   $stmt  = $this->dbconn->prepare($sql);
        //
        //   $stmt->execute([":user_id" => $id]);

        //$res = $stmt ->fetchAll();

        //var_dump($res);

        //$total = count($res);

        //echo $total;
        //exit;

//
//       SELECT COUNT(id)
        // FROM stats
        // GROUP BY EXTRACT(YEAR_MONTH FROM record_date)


        //$this->dbconn->setAttribute( \PDO::ATTR_EMULATE_PREPARES, false );
        //
        // $sql="SELECT `tweet` FROM `ftcl` WHERE `user_id` = :user_id";
        // //$sql="SELECT * FROM :table LIMIT :limit, :offset";
        //
        // $stmt  = $this->dbconn->prepare($sql);
        //
        // $stmt->execute([":user_id" => $id]);

        //$res = $stmt ->fetchAll();

        //$total = count($tweets);

        // Preassign data to the layout
        $this->tpl->addData(['title' => '@' . $user['username'] . ' is capturing invaders !', 'description' => '@' . $user['username'] . ' is playing Cycle Space Invaders, their Hi-Score is ' . $score . ' with ' . $total_records . ' invaders captured so far, chapeau !']);

        // Render a template
        echo $this->tpl->render('export/player', ['tweets' => $tweets, 'total' => $total_records, 'user' => $user, 'last_active' => $last_active, 'score' => $score]);
    }
}
