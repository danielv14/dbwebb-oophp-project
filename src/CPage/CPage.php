<?php
/*
 * Class to represent content on webpage
 */

class CPage extends CContent {

    /**
     * Constructor
     */
    public function __construct($db, $filter) {
        $this->db = $db;
        $this->filter = $filter;
    }

    /**
     * Show selected page
     *
     * @param string $url to the page to show
     * @return object page to show
     */
    public function showPage($url) {

        // Get content
        $sql = "SELECT * FROM Content WHERE type = 'page' AND url = ? AND published <= NOW()";
        $params = array($url);
        $res = $this->db->executeSelectQueryAndFetchAll($sql, $params);

        if(isset($res[0])) {
            $c = $res[0];
        }
        else {
            die(('Misslyckades: sida saknas.'));
        }

        $title = htmlentities($c->title, null, 'UTF-8');
        $data  = $this->filter->doFilter(htmlentities($c->data, null, 'UTF-8'), $c->filter);
         $html = "
      <header>
          <h1>$title</h1>
      </header>

    $data";

        return $html;
    }
}