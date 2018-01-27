<?php

    require_once("gns-blog/init.php");

    $url = explode('/', filter_var(rtrim($_GET['url'], '/')/*, FILTER_SANITIZE_URL*/));
    
    if ( $url[0] == '' || $url[0] == 'index.php' || $url[0] == 'index.html' ) {
        
        $posts = gnsblog(1);
        include_once("views/index.php");
        
    } else if ( $url[0] == 'page' && is_numeric($url[1]) ) {
        
        $pagenum = $url[1];
        
        if ( $pagenum == 0 ) {
            http_response_code(404);
            die();
        }
        
        $posts = gnsblog($pagenum);
        include_once("views/index.php");
        
    } else if ( $url[0] == "feed" ) {
        
        $posts = gnsblog(0);
        echo gnsblog_rss($posts);
        
    } else if ( $url[0] == "json") {
        
        $posts = gnsblog(0);
        
        foreach ( $posts as $post ) {
            unset($post["content"]);
            $postJson[] = $post;
        }
        
        echo json_encode($postJson);
        
    } else {
        
        if ( strpos($_SERVER["QUERY_STRING"], '&') !== false ) {

            $slug = substr($_SERVER["QUERY_STRING"], 0, strpos($_SERVER["QUERY_STRING"], "&"));

        } else if ( strpos($_SERVER["QUERY_STRING"], '?') !== false ) {

            $slug = substr($_SERVER["QUERY_STRING"], 0, strpos($_SERVER["QUERY_STRING"], "?"));

        } else {

            $slug = $_SERVER['QUERY_STRING'];

        }

        $slug = str_replace("/", "", $slug);
        
        $slug = substr($slug, 4);
        $exists = gnsblog_findPost($slug);
        
        if ( $exists == false ) {
            http_response_code(404);
            die();
        } else {
            $post = gnsblog_post($exists["path"]);
            include_once("views/permalink.php");
        }
        
    }
    
?>