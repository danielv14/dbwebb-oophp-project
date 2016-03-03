<?php
/*
 * Class to represent new
 *
 */


 class CBlog extends CContent {

     /**
      * Constructor
      */
     public function __construct($db, $filter) {
         $this->db = $db;
         $this->filter = $filter;
     }


     /**
     * Show blog post
     *
     * @param string $slug for post
     *
     */
    public function showPost($slug) {
        // Get content
        $slugSql = $slug ? 'slug = ?' : '1';
        $sql = "SELECT * FROM Content
                        WHERE
                            type = 'post' AND
                            $slugSql AND
                            published <= NOW()
                        ORDER BY updated DESC";

        $params = array($slug);
        $res = $this->db->executeSelectQueryAndFetchAll($sql, $params);

        $content = "";

        if(isset($res[0])) {
            foreach($res AS $c) {
            $title = htmlentities($c->title, null, 'UTF-8');
            $data = $this->filter->doFilter(htmlentities($c->data, null, 'UTF-8'), $c->filter);

            $content .= "

    <header>
        <h1 class = 'colorful'><a href='blog.php?slug={$c->slug}'>{$title}</a></h1>
        <p class='published'>Publicerad $c->published</p>
    </header>
    <article class=blog>
    {$data}
    </article>
";
            }
        }
        else if($slug) {
            $miletus['main'] = "Det fanns inte en sådan bloggpost.";
        }
        else {
            $miletus['main'] = "Det fanns inga bloggposter.";
        }

        return $content;
    }
    	// Show 3 latest blogposts
        public function showthreePost($slug) {
        // Get content
        $slugSql = $slug ? 'slug = ?' : '1';
        $sql = "SELECT * FROM Content
                        WHERE
                            type = 'post' AND
                            $slugSql AND
                            published <= NOW()
                        ORDER BY id DESC limit 3";

        $params = array($slug);
        $res = $this->db->executeSelectQueryAndFetchAll($sql, $params);

        $content = "";

        if(isset($res[0])) {
            foreach($res AS $c) {
            $title = htmlentities($c->title, null, 'UTF-8');
            $data = $this->filter->doFilter(htmlentities($c->data, null, 'UTF-8'), $c->filter);

            $content .= "

    <header>
        <h1><a href='blog.php?slug={$c->slug}'>{$title}</a></h1>
        <p class='published'>Publicerad $c->published</p>
    </header>
    <div id=section>
    <article class=blog>
    {$data}
    </article>
    </div>
";
            }
        }
        else if($slug) {
            $miletus['main'] = "Det fanns inte en sådan bloggpost.";
        }
        else {
            $miletus['main'] = "Det fanns inga bloggposter.";
        }

        return $content;
    }
    
    

 }