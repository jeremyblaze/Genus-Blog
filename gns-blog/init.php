<?php

    /*

        Welcome to Genus Blog.
        This CMS was built for use entirely by Jeremy Blazé on his projects and with his clients.
        
        To do
        - Images
        - RSS

    */
    
    /* Options */
    
        $postsPerPage = 2;
        
    /* End options */

    require_once("spyc/Spyc.php");
    require_once("parsedown/Parsedown.php");
    
    date_default_timezone_set('Australia/Melbourne');

    function gnsblog($pageNum) {
        
        $data = array();
            
        // Fetch post list
    
            $postList = gnsblog_postList();
                
        // Limit results
    
            global $postsPerPage;
        
            $postCount = count($postList);
            $postOffset = ($pageNum - 1) * $postsPerPage;
            
            $postList = array_slice($postList, $postOffset, $postsPerPage, true);
            $cutPostCount = count($postList);
            
            if ( $cutPostCount == 0 ) {
                http_response_code(404);
                die();
            }
            
        // Pagination stuff
        
            if ( $postOffset != 0 ) {
                $prevPageNum = $pageNum - 1;
                global $gnsblog_prevPage;
                $gnsblog_prevPage = "/page/$prevPageNum";
            }
            
            if ( $cutPostCount + $postOffset < $postCount ) {
                $nextPageNum = $pageNum + 1;
                global $gnsblog_nextPage;
                $gnsblog_nextPage = "/page/$nextPageNum";
            }
            
        // Fetch post details
        
            foreach ( $postList as $postSrc ) {
                $data[] = gnsblog_post($postSrc['path']);
            }
            
        // Return it
        
            return $data;
        
    }
    
    function gnsblog_post($path) {
    
        $postRaw = file_get_contents($path."/post.md");
        
        // Extract info
    
            $justTheDate = substr($path, 6);
            $justTheDate = substr($justTheDate, 0, 10);
            $date = $justTheDate;
            $slug = substr($path, 17);
        
            $optExtract = explode("---", $postRaw);
            
            if ( isset($optExtract[1]) ){
                $optExtract = explode("---", $optExtract[1]);
                $optionsRaw = $optExtract[0];
            }
            
            // Parse options
            
                $post = spyc_load($optionsRaw);
                
                $post["date_raw"] = $date;
                $post["date"] = $post["date_raw"];
                
                $now = date("Y-m-d");
                if ( $post["date_raw"] > $now ) {
                    return null;
                }
                
                $post["slug"] = $slug;
            
        // Extract content
        
            $delim1 = strpos($postRaw, "---");
            $delim2 = strpos($postRaw, "---", $delim1 + strlen("---"));
            $contentStart = strlen($postRaw) - $delim2 - 3;
            
            $contentRaw = substr($postRaw, -$contentStart);
            
            $content = new Parsedown();
            $post["content"] = $content->text($contentRaw);
            
        // Return the post as an array
        
            return $post;
        
    }
    
    function gnsblog_findPost($inputSlug) {
            
        $postList = gnsblog_postList();
        
        $targetFile = false;
        foreach ( $postList as $post ) {
            if ( $post["slug"] == $inputSlug ) {
                $targetFile = $post;
            }
        }
        
        if ( $targetFile != false ) {
            return $targetFile;
        } else {
            return false;
        }
        
    }
    
    function gnsblog_postList() {
        
        $postDirectories = glob('posts' . '/*' , GLOB_ONLYDIR);
        
        $postList = array();
        
        foreach ( $postDirectories as $postFile ) {
            $justTheDate = substr($postFile, 6);
            $justTheDate = substr($justTheDate, 0, 10);
            $item["date"] = $justTheDate;
            $item["path"] = $postFile;
            $item["slug"] = substr($postFile, 17);
            $postList[] = $item;
        }
        
        function sortByDate($a, $b) {
            $a = $a['date'];
            $b = $b['date'];
            
            if ($a == $b) { return 0; }
            
            return ($a > $b) ? -1 : 1;
        }
    
        usort($postList, 'sortByDate');
        
        return $postList;
        
    }
    
?>