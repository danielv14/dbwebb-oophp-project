<?php

class CContent {


  private $db;

  public function __construct($db) {
    $this->db = $db;
  }
  
  
  
   public function getInsertForm($output) { 
        $date = date("Y-m-d H:i:s"); 
        $form = "<form method=post>"; 
            $form .= "<p><a href='admin_blog.php'>Tillbaka till admin-sidan</a></p>"; 
            $form .= "<fieldset>"; 
                $form .= "<legend>Lägg till innehåll</legend>"; 
                $form .= "<p><label>Type:<br/><select name='type'>"; 
                $form .= "<option value='page'>Page</option>"; 
                $form .= "<option value='post'>Post</option>"; 
                $form .= "</select></p>"; 
                $form .= "<p><label>Titel:<br/><input type='text' name='title' value=''/></label></p>"; 
                $form .= "<p><label>Slug:<br/><input type='text' name='slug' value=''/></label></p>"; 
                $form .= "<p><label>Url:<br/><input type='text' name='url' value=''/></label></p>"; 
                $form .= "<p><label>Text:<br/><textarea name='data'></textarea></label></p>"; 
                $form .= "<p><label>Filter:<br/><input type='text' name='filter' value=''/></label></p>"; 
                $form .= "<p><label>Publicerad:<br/><input type='text' value='$date' name='filter' value=''/></label></p>";
                $form .= "<p class=buttons><input type='submit' name='save' value='Spara'/> <input type='reset' value='Återställ'/></p>"; 
            $form .= "</fieldset>"; 
        $form .= "</form>"; 
         
        return $form; 
    } 
    
     public function insertContent($title, $slug, $url, $data, $type, $filter, $published) { 
        $sql = ' 
            INSERT INTO Content (slug, url, type, title, data, filter, published, created) VALUES 
            (?, ?, ?, ?, ?, ?, NOW(), NOW()) 
          '; 
        //If the value is empty set it to NULL 
        $url = empty($url) ? null : $url; 
        $params = array($slug, $url, $type, $title, $data, $filter); 
        $res = $this->db->ExecuteQuery($sql, $params); 
           
        //check if the query went well, if not show information 
        if($res) { 
            header('Location: admin.php'); 
        } 
        else { 
            die('Informationen sparades ej.'); 
        } 
    } 


  
 
  public function createDatabase() {
    $sql = "DROP TABLE IF EXISTS Content;
            CREATE TABLE Content
            (
              id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
              slug CHAR(80) UNIQUE,
              url CHAR(80) UNIQUE,

              type CHAR(80),
              title VARCHAR(80),
              data TEXT,
              filter CHAR(80),

              published DATETIME,
              created DATETIME,
              updated DATETIME,
              deleted DATETIME

            ) ENGINE INNODB CHARACTER SET utf8;";
    $this->db->executeQuery($sql);

  }
 
  

 public function resetDatabase() {
        $sql = "
                INSERT INTO Content (slug, url, type, title, data, filter, published, created) VALUES
  ('hem', 'hem', 'page', 'Hem', 'Detta är min hemsida. Den är skriven i [url=http://en.wikipedia.org/wiki/BBCode]bbcode[/url] vilket innebär att man kan formattera texten till [b]bold[/b] och [i]kursiv stil[/i] samt hantera länkar.\n\nDessutom finns ett filter \"nl2br\" som lägger in <br>-element istället för \\\ n(utan mellanslag), det är smidigt, man kan skriva texten precis som man tänker sig att den skall visas, med radbrytningar.', 'bbcode,nl2br', NOW(), NOW()),
  ('om', 'om', 'page', 'Om', 'Detta är en sida om mig och min webbplats. Den är skriven i [Markdown](http://en.wikipedia.org/wiki/Markdown). Markdown innebär att du får bra kontroll över innehållet i din sida, du kan formattera och sätta rubriker, men du behöver inte bry dig om HTML.\n\nRubrik nivå 2\n-------------\n\nDu skriver enkla styrtecken för att formattera texten som **fetstil** och *kursiv*. Det finns ett speciellt sätt att länka, skapa tabeller och så vidare.\n\n###Rubrik nivå 3\n\nNär man skriver i markdown så blir det läsbart även som textfil och det är lite av tanken med markdown.', 'markdown', NOW(), NOW()),
  ('blogpost-1', NULL, 'post', 'Välkommen till min blogg!', 'Detta är en bloggpost.\n\nNär det finns länkar till andra webbplatser så kommer de länkarna att bli klickbara.\n\nhttp://dbwebb.se är ett exempel på en länk som blir klickbar.', 'link,nl2br', NOW(), NOW()),
  ('blogpost-2', NULL, 'post', 'Nu har sommaren kommit', 'Detta är en bloggpost som berättar att sommaren har kommit, ett budskap som kräver en bloggpost.', 'nl2br', NOW(), NOW()),
  ('blogpost-3', NULL, 'post', 'Nu har hösten kommit', 'Detta är en bloggpost som berättar att sommaren har kommit, ett budskap som kräver en bloggpost', 'nl2br', NOW(), NOW());
            ";
        $this->db->executeQuery($sql);

  }
  



  

  public function getUrlToContent($content) {
        switch($content->type) {
            case 'page': return "page.php?url=$content->url"; break;
            case 'post': return "blog.php?slug=$content->slug"; break;
            default: return null; break;
        }
    }


  public function slugify($str) {
    $str = mb_strtolower(trim($str));
    $str = str_replace(array('å','ä','ö'), array('a','a','o'), $str);
    $str = preg_replace('/[^a-z0-9-]/', '-', $str);
    $str = trim(preg_replace('/-+/', '-', $str), '-');
    return $str;
  }

  public function getAllContent() {
    $sql = "SELECT *, (published <= NOW()) AS available FROM Content WHERE deleted IS NULL";
    $res = $this->db->executeSelectQueryAndFetchAll($sql);
    $items = null;
    foreach($res AS $key => $val) {
      $items .= "<li>"  . htmlentities($val->title, null, 'UTF-8') . " 
      <span class='red'><a href='blog_edit.php?id={$val->id}'>editera</a>
     
      <a href='blog_delete.php?id={$val->id}'>ta bort</a></span></li>\n";
    }

    return $items;
  }
  
  // Add content on create_blog.php
   public function addContent(){ 
        $output = null; 
          $sql = "INSERT INTO Content (title, slug, url, data, type, filter, published) VALUES (?,?,?,?,?,?,?)"; 
          $params = array($this->title, $this->slug, $this->url, $this->data, $this->type, $this->filter, $this->published); 
          $res = $this->_db->change($sql, $params); 
          if($res) { 
            $output = 'Informationen sparades.'; 
          } 
          else { 
            $output = 'Informationen sparades EJ.<br><pre>' . print_r($this->_db->errorInfo(), 1) . '</pre>'; 
          } 
        return $output; 

    } 
  
  
  public function get3Blogposts() {
    $sql = "SELECT * FROM `Content` WHERE deleted is NULL order by id desc limit 3;";
    $res = $this->db->executeSelectQueryAndFetchAll($sql);
    $items = null;
    foreach($res AS $key => $val) {
      $items .= "<li>"  . htmlentities($val->title, null, 'UTF-8') . " <span class ='red'>
     
      <a href='" . $this->getUrlToContent($val) . "'>visa</a></span>
      </li>\n";
    }

    return $items;
  }


  public function getItem($id) {
    $sql = "SELECT *
    FROM Content
    WHERE
      TYPE = 'page' AND
      url = ? AND
      published <= NOW();
    ";
    $params = array($id);

    $res = $this->db->executeSelectQueryAndFetchAll($sql, $params, true);

    if (isset($res[0])) {
      $tmp = $res[0];
    } else {
      die(("id saknades"));
    }
    return $tmp;
  }


  public function getContent($id) {
        $sql = 'SELECT * FROM Content WHERE id = ?';
        $params = array($id);
        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql, $params);
        if(isset($res[0])) {
            $c = $res[0];
        }
        else {
            die(('Misslyckades: id saknas.'));
        }

        return $c;
    }

  public function updateItem() {

        $url             = strip_tags($_POST['url']);
        $type         = strip_tags($_POST['type']);
        $published = strip_tags($_POST['published']);
        $filter     = strip_tags($_POST['filter']);
        $title         = $_POST['title'];
        $data         =  $_POST['data'];
        $slug         = $_POST['slug'];
        $id             = strip_tags($_POST['id']);

        $sql =
        'UPDATE Content SET
            title         = ?,
            slug            = ?,
            url                = ?,
            data            = ?,
            type            = ?,
            filter        = ?,
            published = ?,
            updated        = NOW()
        WHERE
            id = ?';

        $url = empty($url) ? null : $url;
        $filter = empty($filter) ? 'markdown' : $filter;
        $params = array($title, $slug, $url, $data, $type, $filter, $published, $id);
        $res = $this->db->ExecuteQuery($sql, $params);
        if($res) {
            $output = 'Informationen sparades.';
        }
        else {
            $output = 'Informationen sparades EJ.';
        }

        return $output;
  }

   public function setTitle($title) {

        $slug = strip_tags($title);
        $slug = $this->slugify($title);

        $sql = "INSERT INTO Content(title, slug, created) VALUES(?, ?, NOW())";

        $params = array($title, $slug);
        $res = $this->db->ExecuteQuery($sql, $params);

        if($res) {
            return '"' . htmlentities($title) . '" har skapats';
        }
        else {
            "Sidan kunde inte skapas.";
        }
    }

  public function deleteItem($id, $title) {
        $sql =
        'DELETE  FROM `Content` WHERE id = ?';
        $params = array($id);
        $res = $this->db->ExecuteQuery($sql, $params);
        if($res) {
            return '"' . $title . '"' . ' har tagits bort';
        }
        else {
            return "Sidan kan inte tas bort";
        }
    }
 
 


    
    
       
    

}