<?php
/**
 * Class for Database Manager.
 *
 */
class CDatabaseManager
{
    private $db        = null;
    private $title      = null;
    private $genre      = null;
    private $genres      = null;
    private $hits         = 8;
    private $page         = 1;
    private $year1        = null;
    private $year2        = null;
    private $params         = array();
    private $orderby      = 'id';
    private $order        = 'asc';
    private $max            = 0;
    private $rows        = 0;

    //Constructor
    public function __construct($database)
    {
        $this->db = $database;
    }
    private function GetParameters()
    {
        $this->title    = isset($_GET['title']) ? $_GET['title'] : null;
        $this->genre    = isset($_GET['genre']) ? $_GET['genre'] : null;
        $this->hits     = isset($_GET['hits'])  ? $_GET['hits']  : 8;
        $this->page     = isset($_GET['page'])  ? $_GET['page']  : 1;
        $this->year1    = isset($_GET['year1']) && !empty($_GET['year1']) ? $_GET['year1'] : null;
        $this->year2    = isset($_GET['year2']) && !empty($_GET['year2']) ? $_GET['year2'] : null;
        $this->orderby  = isset($_GET['orderby']) ? strtolower($_GET['orderby']) : 'id';
        $this->order    = isset($_GET['order'])   ? strtolower($_GET['order'])   : 'asc';

        // Check that incoming parameters are valid
        is_numeric($this->hits) or die('Check: Hits must be numeric.');
        is_numeric($this->page) or die('Check: Page must be numeric.');
        is_numeric($this->year1) || !isset($this->year1)  or die('Check: Year must be numeric or not set.');
        is_numeric($this->year2) || !isset($this->year2)  or die('Check: Year must be numeric or not set.');
    }
    
     /**
     * Use the current querystring as base, modify it according to $options and return the modified query string.
     *
     * @param array $options to set/change.
     * @param string $prepend this to the resulting query string
     * @return string with an updated query string.
     */
    private function GetQueryString($options=array(), $prepend='?') {
      // parse query string into array
      $query = array();
      parse_str($_SERVER['QUERY_STRING'], $query);

      // Modify the existing query string with new options
      $query = array_merge($query, $options);

      // Return the modified querystring
      return $prepend . htmlentities(http_build_query($query));
    }
    /**
     * Create links for hits per page.
     *
     * @param array $hits a list of hits-options to display.
     * @param array $current value.
     * @return string as a link to this page.
     */
    private function GetHitsPerPage($hits, $current=null) {
      $nav = "Träffar per sida: ";
      foreach($hits AS $val) {
        if($current == $val) {
          $nav .= "$val ";
        }
        else {
          $nav .= "<a href='" . $this->GetQueryString(array('hits' => $val)) . "'>$val</a> ";
        }
      }
      return $nav;
    }



    /**
     * Create navigation among pages.
     *
     * @param integer $hits per page.
     * @param integer $page current page.
     * @param integer $max number of pages.
     * @param integer $min is the first page number, usually 0 or 1.
     * @return string as a link to this page.
     */
    private function GetPageNavigation($hits, $page, $max, $min=1)
    {
          $nav  = ($page != $min) ? "<a href='" . $this->GetQueryString(array('page' => $min)) . "'>&lt;&lt;</a> " : '&lt;&lt; ';
          $nav .= ($page > $min) ? "<a href='" .  $this->GetQueryString(array('page' => ($page > $min ? $page - 1 : $min) )) . "'>&lt;</a> " : '&lt; ';

          for($i=$min; $i<=$max; $i++) {
            if($page == $i) {
              $nav .= "$i ";
            }
            else {
              $nav .= "<a href='" . $this->GetQueryString(array('page' => $i)) . "'>$i</a> ";
            }
          }

          $nav .= ($page < $max) ? "<a href='" . $this->GetQueryString(array('page' => ($page < $max ? $page + 1 : $max) )) . "'>&gt;</a> " : '&gt; ';
          $nav .= ($page != $max) ? "<a href='" . $this->GetQueryString(array('page' => $max)) . "'>&gt;&gt;</a> " : '&gt;&gt; ';

          return $nav;
    }
    /**
     * Function to create links for sorting
     *
     * @param string $column the name of the database column to sort by
     * @return string with links to order by column.
     */
    private function Orderby($column)
    {
          $nav  = "<a href='" . $this->GetQueryString(array('orderby'=>$column, 'order'=>'asc')) . "'>&darr;</a>";
          $nav .= "<a href='" . $this->GetQueryString(array('orderby'=>$column, 'order'=>'desc')) . "'>&uarr;</a>";
          return "<span class='orderby'>" . $nav . "</span>";
    }
    
    
    
    private function GetData()
    {
        // Select by title
        if($this->title)
        {
            $this->params[] = $this->title;
            return ' AND title LIKE ?';
        }
       
        $where = null;
        // Select by year
        if($this->year1)
        {
              $where .= ' AND year >= ?';
              $this->params[] = $this->year1;
        }
        if($this->year2)
        {
              $where .= ' AND year <= ?';
              $this->params[] = $this->year2;
        }
        if(isset($where))
        {
            return $where;
        }
        
        // Select by genre
        if($this->genre)
        {
            $this->params[] = $this->genre;
             return ' AND G.name = ?';
        }
        
        else
        {
            return null;
        }
        
       }     
      
    private function GetGenres()
    {
        
        $sql = '
          SELECT DISTINCT G.name
          FROM Genre AS G
            INNER JOIN Movie2Genre AS M2G
              ON G.id = M2G.idGenre
        ';
        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql);

        foreach($res as $val)
        {
              if($val->name == $this->genre)
              {
                  $this->genres .= "$val->name ";
              }
              else
              {
                  $this->genres .= "<a href='" . $this->GetQueryString(array('genre' => $val->name)) . "'>{$val->name}</a> ";
              }
        }
    }
   

    private function GetWhereStatement()
    {
        //WHERE 1 lets us use additional conditions with AND... it has no impact on execution time.
        $where = " WHERE 1 " . $this->GetData();
        if($where == " WHERE 1 ")
        {
            return null;
        }
        else
        {
            return $where;
        }
    }
    private function GetLimit()
    {
        // Pagination
        if($this->hits && $this->page)
        {
            return " LIMIT {$this->hits} OFFSET " . (($this->page - 1) * $this->hits);

        }
    }
   
 




} 