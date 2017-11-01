<?php

namespace Palmtree\WordPress\Pagination;

class Pagination
{
    protected $query;
    protected $links;

    protected $args = [
        'show_all'  => true,
        'prev_text' => '&laquo;',
        'next_text' => '&raquo;',
    ];

    /**
     * Pagination constructor.
     *
     * @param \WP_Query $query
     * @param array     $args
     */
    public function __construct($query = null, $args = [])
    {
        $this->query = $query ? $query : $GLOBALS['wp_query'];

        foreach ($args as $key => $value) {
            $this->args[$key] = $value;
        }
    }

    /**
     * Returns bootstrap HTML output for our links
     *
     * @return string
     */
    public function getHtml()
    {
        $links = $this->getLinks();

        if (!$links) {
            return '';
        }

        $output = '<nav aria-label="Page navigation">
		          <ul class="pagination justify-content-center">';

        $find    = ['page-numbers'];
        $replace = ['page-link'];

        foreach ($links as $link) {
            $itemClass = 'page-item';

            if (stripos($link, 'current') > -1) {
                $itemClass .= ' active';
            }

            $output .= '<li class="' . $itemClass . '">' . str_replace($find, $replace, $link) . '</li>';
        }

        $output .= '</ul>
		           </nav>';

        return $output;
    }

    /**
     * @return array
     */
    public function getLinks()
    {
        if (!$this->links) {
            $this->generateLinks();
        }

        return $this->links;
    }

    /**
     *
     */
    protected function generateLinks()
    {
        $paged = get_query_var('paged') ? get_query_var('paged') : 1;
        $big   = 999999999; // need an unlikely integer

        $args = array_replace($this->getArgs(), [
            'base'    => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
            'format'  => '?paged=%#%',
            'current' => $paged,
            'total'   => $this->query->max_num_pages,
            'type'    => 'array',
        ]);

        $this->links = paginate_links($args);
    }

    /**
     * @return array
     */
    public function getArgs()
    {
        return $this->args;
    }
}
