<?php

namespace app\template;

use flight\template\View;

class LayoutView extends View
{
    public string $layout = 'layouts/main';

    /**
     * Renders a template with layout and returns the result
     *
     * @param string $file Template file
     * @param array|null $data Template data
     * @return string
     */
    public function renderWithLayout(string $file, ?array $data = null): string
    {
        // Render the content
        $content = $this->fetch($file, $data);
        
        // Render with layout
        return $this->fetch($this->layout, array_merge($data ?? [], [
            'content' => $content
        ]));
    }
}
