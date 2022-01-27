<?php

namespace CycleSpaceInvaders\Controllers;

class HomeController extends Controller
{

    // Per Page
    private $ppage;

    public function __construct()
    {
        parent::__construct();
    }

    public function home()//: string
    {

        // Fetch recent tweets

        $sql = "SELECT *, t.id, t.created_at
              FROM " . $_ENV['DB_TWEET_TABLE'] . " t
              LEFT JOIN " . $_ENV['DB_USER_TABLE'] . " u
              ON t.user_id=u.id
              ORDER BY t.created_at DESC
              LIMIT 10";

        $stmt = $this->dbconn->prepare($sql);

        $stmt->execute();

        $tweets = $stmt->fetchAll();


        // Get random users
        $sql = "SELECT *
                FROM " . $_ENV['DB_USER_TABLE'] . "
                ORDER BY RAND()
                LIMIT 12";

        $stmt = $this->dbconn->prepare($sql);

        $stmt->execute();

        $users = $stmt->fetchAll();


        // Get total tweet count
        $sql = "select count(*) as total_records from " . $_ENV['DB_TWEET_TABLE'];

        $stmt = $this->dbconn->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch();
        $total_tweets = $row['total_records'];

        // Get total user count
        $sql = "select count(*) as total_records from " . $_ENV['DB_USER_TABLE'];

        $stmt = $this->dbconn->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch();
        $total_users = $row['total_records'];

        //todo - this need to be passed to all pages ?
        $high_score = "12345";


        // by score
        $sql = "SELECT *
        FROM users
        RIGHT JOIN (
        SELECT user_id,
        COUNT(*) AS tweet_count,
        SUM(score) AS total_score

        FROM tweets
        GROUP BY user_id
        ) tweet_counts ON tweet_counts.user_id = users.id
        ORDER BY total_score DESC
        LIMIT 5";

        $stmt = $this->dbconn->prepare($sql);
        $stmt->execute();

        $leaders = $stmt->fetchAll();

        $year = date("Y");

        // Preassign data to the layout
        $this->tpl->addData(['title' => 'Home',
            'description' => 'Cycle Space has been INVADED ! Join ' . $total_users . ' players who are capturing these invaders #FreeTheCycleLanes.',
            'layout'
        ]);
        // Render a template
        echo $this->tpl->render('home', ['tweets' => $tweets, 'players' => $users, 'total_invaders' => $total_tweets, 'total_players' => $total_users, 'high_score' => $high_score, 'leaders' => $leaders, 'year' => $year]);
    }
}
