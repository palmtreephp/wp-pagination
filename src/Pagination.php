<?php declare(strict_types=1);

namespace Palmtree\WordPress\Pagination;

class Pagination
{
    /** @var \WP_Query */
    private $query;
    /** @var array|null */
    private $links;
    /** @var array Args passed to paginate_links */
    private $args = [
        'show_all' => true,
        'prev_text' => '&laquo;',
        'next_text' => '&raquo;',
    ];

    public function __construct(?\WP_Query $query = null, array $args = [])
    {
        $this->query = $query ?? $GLOBALS['wp_query'];

        foreach ($args as $key => $value) {
            $this->args[$key] = $value;
        }
    }

    public function getQuery(): \WP_Query
    {
        return $this->query;
    }

    public function setQuery(\WP_Query $query): self
    {
        $this->query = $query;

        return $this;
    }

    public function getArgs(): array
    {
        return $this->args;
    }

    public function setArgs(array $args): self
    {
        $this->args = $args;

        return $this;
    }

    public function addArg(string $key, $value): self
    {
        $this->args[$key] = $value;

        return $this;
    }

    /**
     * Returns bootstrap HTML output for our links.
     */
    public function getHtml(): string
    {
        $links = $this->getLinks();

        if (\count($links) === 0) {
            return '';
        }

        $output = '<nav aria-label="Page navigation">
		          <ul class="pagination justify-content-center">';

        $find = ['page-numbers'];
        $replace = ['page-link'];

        foreach ($links as $link) {
            $itemClass = 'page-item';

            if (stripos($link, 'current') !== false) {
                $itemClass .= ' active';
            }

            $output .= '<li class="' . $itemClass . '">' . str_replace($find, $replace, $link) . '</li>';
        }

        $output .= '</ul>
		           </nav>';

        return $output;
    }

    public function getLinks(): array
    {
        if (!isset($this->links)) {
            $paged = get_query_var('paged') ?: 1;

            $args = array_replace($this->getArgs(), [
                'base' => str_replace(\PHP_INT_MAX, '%#%', esc_url(get_pagenum_link(\PHP_INT_MAX))),
                'format' => '?paged=%#%',
                'current' => $paged,
                'total' => $this->query->max_num_pages,
                'type' => 'array',
            ]);

            $this->links = paginate_links($args) ?? [];
        }

        return $this->links;
    }

    public function __toString(): string
    {
        return $this->getHtml();
    }
}
