<?php

class wpCSL_notifications__slplus {

    function __construct($params) {
        foreach ($params as $name => $value) {
            $this->$name = $value;
        }
    }

    function add_notice($level = 1, $content, $link = null) {
        $this->notices[] = new wpCSL_notifications_notice__slplus(
            array(
                'level' => $level,
                'content' => $content,
                'link' => $link
            )
        );
    }

    function display() {
        echo $this->get();
    }
    function get($simple=false) {

        // No need to do anything if there aren't any notices
        if (!isset($this->notices)) return;

        foreach ($this->notices as $notice) {
            $levels[$notice->level][] = $notice;
        }

        ksort($levels, SORT_NUMERIC);
        $difference = max(array_keys($levels));

        $notice_output = '';
        foreach ($levels as $key => $value) {
            if (!$simple) {
                $color = round((($key-1)*(255/$difference)));
                $notice_output .= "<div id='{$this->prefix}_notice' class='updated fade'
                    style='background-color: rgb(255, ".$color.", 25);'>\n";
                $notice_output .= sprintf(
                    __('<p><strong><a href="%1$s">'.$this->name.
                    '</a> needs attention: </strong>'),
                    $this->url
                );
                $notice_output .= "<ul>\n";
            }
            foreach ($value as $notice) {
                if (!$simple) { $notice_output .= '<li>'; }
                $notice_output .= $notice->display();
                if (!$simple) { $notice_output .= '</li>'; }
                $notice_output .= "\n";
            }
            if (!$simple) { 
                $notice_output .= "</ul>\n";
                $notice_output .= "</p></div>\n";
            }
        }

        return $notice_output;
    }
}

class wpCSL_notifications_notice__slplus {

    function __construct($params) {
        foreach($params as $name => $value) {
            $this->$name = $value;
        }
    }

    function display() {
        $retval = $this->content;
        if ( isset($this->link)     && 
             !is_null($this->link)  && 
             ($this->link != '')
            ) {
           $retval .= " (<a href=\"{$this->link}\">Details</a>)";
        }
        return $retval;
    }
}

?>
