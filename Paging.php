<?php
/**
 * Class Paging
 */
class Paging
{
    protected $base_url         = "";       // basic link url
    protected $page_rows        = 0;        // show rows per page
    protected $total_rows       = 0;        // total rows
    protected $display_always   = TRUE;     // show always if has only 1 page.
    protected $fixed_page_num       = 5;    // show pages
    
    protected $first_link = '<i class="fa fa-angle-double-left"></i>';  // first button html
    protected $prev_link = '<i class="fa fa-angle-left"></i>';          // prev button html
    protected $next_link = '<i class="fa fa-angle-right">';             // next button html
    protected $last_link = '<i class="fa fa-angle-double-right"></i>';  // last button html
    
    protected $full_tag_open = '<ul class="pagination pagination-sm">';   // wrap tag (open)
    protected $full_tag_close = '</ul>';                                  // wrap tag (close)
    protected $item_tag_open = "<li>";                                    // button wrap tag (open)
    protected $item_tag_close = "</li>";                                  // button wrap tag (close)
    protected $cur_tag_open = "<li class='active'>";                      // current button wrap tag (open)
    protected $cur_tag_close = "</li>";                                   // current button wrap tag (close)
    protected $first_tag_open = '<li class="paging-first">';              // first button wrap tag (open)
    protected $first_tag_close = '</li>';                                 // first button wrap tag (close)
    protected $last_tag_open = '<li class="paging-last">';                // last button wrap tag (open)
    protected $last_tag_close = '</li>';                                  // last button wrap tag (close)
    protected $next_tag_open = '<li class="paging-next">';                // next button wrap tag (open)
    protected $next_tag_close = '</li>';                                  // next button wrap tag (close)
    protected $prev_tag_open = '<li class="paging-prev">';                // prev button wrap tag (open)
    protected $prev_tag_close = '</li>';                                  // prev button wrap tag (close)
    
    protected $page_param   = "page";                                     // paramaeter name for page
    
    protected $add_param = "";                                            // additional parameter
    
    protected $display_pages = TRUE;
    
    protected $disable_first_link   = TRUE; // if current page is 1 then disabled first button
    protected $disable_last_link    = TRUE; // if current page is last then disabled last button
    protected $disable_prev_link    = TRUE; // if current page is 1 then disabled prev button
    protected $disable_next_link    = TRUE; // if current page is last then disabled next button
    
    protected $display_first_always = FALSE; // if current page is 1 then show first button
    protected $display_last_always  = FALSE; // if current page is last then show last button
    protected $display_prev_always  = FALSE; // if current page is 1 then show prev button
    protected $display_next_always  = FALSE; // if current page is last then show next button
    
    protected $disabled_first_tag_open  = '<li class="paging-first disabled">';
    protected $disabled_first_tag_close = '</li>';
    protected $disabled_last_tag_open   = '<li class="paging-last disabled">';
    protected $disabled_last_tag_close  = '</a></li>';
    protected $disabled_prev_tag_open   = '<li class="paging-prev disabled">';
    protected $disabled_prev_tag_close  = '</li>';
    protected $disabled_next_tag_open   = '<li class="paging-next disabled">';
    protected $disabled_next_tag_close  = '</li>';
    
    // 내부 사용변수
    protected $page             = 1;    // current page
    
    function __construct($params = array())
    {
        $this->initialize($params);
    }
    
    // 넘겨받은 설정값으로 세팅값을 변경한다.
    public function initialize(array $params = array())
    {
        foreach ($params as $key => $val)
        {
            if (property_exists($this, $key))
            {
                $this->$key = $val;
            }
        }
        return $this;
    }
    
    public function create()
    {
        /*
         * Only use in codeigniter for automatic add parameter
        $this->CI =& get_instance();

    	if( empty($this->add_param) ) {
    		$get_array = $_GET;
    		unset($get_array['page']);
			$this->add_param = http_build_query($get_array);
			
			if(! empty($this->add_param)) {
				$this->add_param = "&".$this->add_param;
			}
    	}
		
		if( empty($this->base_url)) {
			$this->base_url = current_url();
		}
        */

        // if total rows 0 return
        if ($this->total_rows == 0 OR $this->page_rows == 0) return '';
        
        // calc total page
        $num_pages = (int) ceil($this->total_rows / $this->page_rows);
 
        // if set display_always false and total page = 1 then
        if ($this->display_always === FALSE AND $num_pages === 1) return '';

        $this->fixed_page_num = (int) $this->fixed_page_num;
        if ($this->fixed_page_num < 0) return "";       
 
        // link url set
        $base_url = trim($this->base_url);      
 
        // if is not isset $this->page
        $this->page =  (isset($_GET[$this->page_param]) && $_GET[$this->page_param])?$_GET[$this->page_param]:1;
        $this->cur_page = (int) $this->page;        

        if ($this->cur_page > $num_pages)
        {
            $this->cur_page = $num_pages;
        }
                
        $uri_page_number = $this->cur_page;
 
        // calc start, end number
        $start = (ceil($this->cur_page / $this->fixed_page_num) - 1) * $this->fixed_page_num + 1 + 1; // Last plus one is for loop statement starts $start - 1 
        $end   = (ceil($this->cur_page / $this->fixed_page_num) == ceil($num_pages / $this->fixed_page_num)) ? $num_pages : ceil($this->cur_page / $this->fixed_page_num) * $this->fixed_page_num;
        
 
        // init output variable;
        $output = '';
 
        // firest button
        if ($this->first_link !== FALSE)
        {           
            if ($this->display_first_always === TRUE AND $this->disable_first_link === TRUE AND $this->cur_page == 1)
            {
                $output .= $this->disabled_first_tag_open . "<span>" . $this->first_link . "</span>" . $this->disabled_first_tag_close;
            }
            else if ($this->display_first_always === TRUE OR $this->cur_page != 1)
            {
                $output .= $this->first_tag_open.'<a href="'.$base_url.'?'.$this->page_param.'=1'.$this->add_param.'">'.$this->first_link.'</a>'.$this->first_tag_close;
            }
        }
 
        // prev button
        if ($this->prev_link !== FALSE)
        {
            if ($this->display_prev_always === TRUE AND $this->disable_prev_link === TRUE AND $this->cur_page == 1)
            {
                $output .= $this->disabled_prev_tag_open . "<span>" . $this->prev_link . "</span>" . $this->disabled_prev_tag_close;
            }
            else if ($this->display_prev_always === TRUE OR $this->cur_page != 1)
            {
                // calc prev page
                $i = ($uri_page_number == 1) ? 1 : ( $uri_page_number - 1);
                
                $output .= $this->prev_tag_open.'<a href="'.$base_url.'?'.$this->page_param."=".$i.$this->add_param.'">'
                        .$this->prev_link.'</a>'.$this->prev_tag_close;
            }
        }
 
        // page buttons
        if ($this->display_pages !== FALSE)
        {           
            for ($loop = $start -1; $loop <= $end; $loop++)
            {
                $i = $loop;             
 
                if ($i >= 1)
                {
                    if ($this->cur_page == $loop)
                    {
                        // if is current page
                        $output .= $this->cur_tag_open.'<span>'.$loop.'</span>'.$this->cur_tag_close;
                    }
                    else
                    {                       
                        $output .= $this->item_tag_open.'<a href="'.$base_url.'?'.$this->page_param."=".$loop.$this->add_param.'">'.$loop.'</a>'.$this->item_tag_close;
                    }
                }
            }
        }
 
        // next button
        if ($this->next_link !== FALSE)
        {
            if ($this->display_next_always === TRUE AND $this->disable_next_link === TRUE AND $this->cur_page == $num_pages)
            {
                $output .= $this->disabled_next_tag_open . "<span>" . $this->next_link . "</span>" . $this->disabled_next_tag_close;
            }
            else if ($this->display_next_always === TRUE OR $this->cur_page != $num_pages)
            {
                // calc next page
                $i = ($this->cur_page == $num_pages)? $num_pages : $this->cur_page + 1;
                
                $output .= $this->next_tag_open.'<a href="'.$base_url.'?'.$this->page_param."=".$i.$this->add_param.'">'.$this->next_link.'</a>'.$this->next_tag_close;
            }
        }
 
        // last button
        if ($this->last_link !== FALSE)
        {
            if ($this->display_last_always === TRUE AND $this->disable_last_link === TRUE AND $this->cur_page == $num_pages)
            {
                $output .= $this->disabled_last_tag_open . "<span>" . $this->last_link . "</span>" . $this->disabled_last_tag_close;
            }
            else if ($this->display_last_always === TRUE OR $this->cur_page != $num_pages)
            {
                $i = $num_pages;
 
                $output .= $this->last_tag_open.'<a href="'.$base_url.'?'.$this->page_param."=".$i.$this->add_param.'">'.$this->last_link.'</a>'.$this->last_tag_close;
            }
        }

        $output = preg_replace('#([^:])//+#', '\\1/', $output);
        
        return $this->full_tag_open.$output.$this->full_tag_close;
    }
    
    
    function __destruct()
    {       
    }
}
