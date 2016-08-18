# php-pagination-class
PHP pagination class for korean style

# Usage
<pre><code>
<?php
$config = array(
    'base_url' => 'http://www.example.com/bbs/board.php',
    'page_rows' => 10,
    'total_rows' => 200
);
$paging = new Paging();
$pagination = $paging->create();

echo $pagination;
</code></pre>

# Usage ON Codeigniter
<pre><code>
<?php
$config = array(
    'base_url' => 'http://www.example.com/bbs/board.php',
    'page_rows' => 10,
    'total_rows' => 200
);
$this->load->library('paging');
$this->paging->initialize($config);
$pagination = $this->paging->create();

echo $pagination;
</code></pre>