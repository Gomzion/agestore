<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Pagination extends CI_Pagination {

	public function __construct()
	{
		parent::__construct();
	}

	public function create_links()
	{
		if ($this->total_pages() == 1) {
			return '';
		}

		$output = '<ul>';

		// Previous link
		if ($this->prev_link !== FALSE) {
			$prev_num = ($this->cur_page > 1) ? $this->cur_page - 1 : 1;
			$output .= '<li class="prev"><a href="'.$this->url($prev_num).'">'.$this->prev_link.'</a></li>';
		}

		// Numbered links
		$num_links = $this->num_links;
		$num_pages = $this->total_pages();
		$current_page = $this->cur_page;

		$start = max($current_page - 2, 1);
		$end = min($current_page + 2, $num_pages);

		for ($i = $start; $i <= $end; $i++) {
			if ($i == $current_page) {
				$output .= '<li><a href="#" class="active">'.$i.'</a></li>';
			} else {
				$output .= '<li><a href="'.$this->url($i).'">'.$i.'</a></li>';
			}
		}

		// Next link
		if ($this->next_link !== FALSE) {
			$next_num = ($this->cur_page < $num_pages) ? $this->cur_page + 1 : $num_pages;
			$output .= '<li class="next"><a href="'.$this->url($next_num).'">'.$this->next_link.'</a></li>';
		}

		$output .= '</ul>';

		return $output;
	}



	public function total_pages()
	{
		return ceil($this->total_rows / $this->per_page);
	}

	public function url($page)
	{
		if ($page < 1) {
			return $this->base_url;
		} else {
			return $this->base_url . $this->suffix . $this->query_string_segment . '=' . $page;
		}
	}
}
