<?php

class DB{
    private static $dbh = null;
    private static  $query , $res , $data;
    public function __construct() {
        self::$dbh = new PDO("mysql:host=localhost;dbname=mmcoder_php_project","root","");
    }

    public static function table($table){
        self::$query = 'select * from ' .$table;
        $db = new DB;
        return $db;
    }

    public function execute($array = []){
      
        self::$res = self::$dbh->prepare(self::$query);
        self::$res->execute($array);
    }

    function get(){
        $this->execute();
        self::$data = self::$res->fetchAll(PDO::FETCH_ASSOC);
        return self::$data;
    }

    function getOne(){
        $this->execute();
        self::$data = self::$res->fetch(PDO::FETCH_ASSOC);
        return self::$data;
    }

    function orderBy($column,$order){
        self::$query .= ' order by '.$column.' ' .$order;
        return $this;
    }

    function getCount(){
        $this->execute();
        return self::$res->rowCount();
    }

    function where(){
        $numargs = func_num_args();
        $parameters = func_get_args();
        if($numargs == 2){
            self::$query .= ' where '.$parameters[0].' = "'.$parameters[1].'"' ;
            // echo self::$query;
        }

        if($numargs == 3){  
            self::$query .= ' where '.$parameters[0].' '.$parameters[1].' '.$parameters[2] ;    
        }
    
        return $this;
    }

    function andWhere(){
        $numargs = func_num_args();
        $parameters = func_get_args();
        if($numargs == 2){
            self::$query .= ' and '.$parameters[0].' = "'.$parameters[1].'"' ;
          
        }

        if($numargs == 3){  
            self::$query .= ' and '.$parameters[0].' '.$parameters[1].' "'.$parameters[2].'"' ;    
        }
    
        return $this;
    }

    function orWhere(){
        $numargs = func_num_args();
        $parameters = func_get_args();
        if($numargs == 2){
            self::$query .= ' or '.$parameters[0].' = "'.$parameters[1].'"' ;
          echo self::$query;
        }

        if($numargs == 3){  
            self::$query .= ' or '.$parameters[0].' '.$parameters[1].' "'.$parameters[2].'"' ; 
            echo self::$query;   
        }
    
        return $this;
    }

    static function create($table,$insertData){
 
        $keyStr = '';
        $valStr = '';
        
      
        foreach($insertData as $key=>$value){
            $keyStr .= $key.',';
            $valStr .= '?,';
        }

        $keyStr = substr($keyStr,0,-1);
        $valStr = substr($valStr,0,-1);
  

        self::$query = 'INSERT INTO '.$table.'('.$keyStr.') VALUES ('.$valStr.')';

      
        $DB = new DB();
        $DB->execute(array_values($insertData));
     
        return self::$dbh->lastInsertId();
        

    }

    static function update($table,$updateData,$id){
 
        $keyStr = '';
        
        foreach(array_keys($updateData) as $key){
            $keyStr .= $key.' = ? ,';
        }

        $keyStr = substr($keyStr,0,-1);
      
        self::$query = 'UPDATE '.$table.' SET '.$keyStr.' where id = '.$id;
       

        $DB = new DB();
        $DB->execute(array_values($updateData));

        // echo 'success';
        return $id;
    }

    static function delete($table,$id){
        self::$query = 'DELETE FROM '.$table.' 
        WHERE id = ?';

        $DB = new DB();
        $DB->execute([$id]);

    }

    function paginate($page_per_record){

        //total record
        $total_records = $this->getCount();

        //links
        $links = [];


        //prev and next
        $prev= '';
        $next= '';

        for($i = 1 ; $i <= ceil($total_records/$page_per_record) ;$i++){
            array_push($links, '?page='.$i);
        }


        //dynamic query , links , prev  next 
        if(isset($_GET['page']) ){
            if($_GET['page'] > 0 && $_GET['page'] <= ceil($total_records/$page_per_record) ){
                self::$query .= ' LIMIT '.$page_per_record*($_GET['page']-1).' , '.$page_per_record;

                $prev= isset($links[$_GET['page']-2]) ? $links[$_GET['page']-2] : null;
                $next= isset($links[$_GET['page']]) ? $links[$_GET['page']] : null;

            }else{
                self::$query .= ' LIMIT 0 , '.$page_per_record;
                $prev= null;
                $next= isset($links[1])  ? $links[1] : null;
            }        
        }else{
            self::$query .= ' LIMIT 0 , '.$page_per_record;
            $prev= null;
            $next= isset($links[1])  ? $links[1] : null;
        }


        //data
        $data = $this->get();

        return [
            'total' =>$total_records,
            'links' => $links ,
            'data' => $data,
            'prev' => $prev,
            'next' => $next
        ];

    }

    static function raw($sql){
        self::$query = $sql;

        $DB = new DB();
        return $DB;
    }
}







?>