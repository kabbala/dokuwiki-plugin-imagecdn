<?php
/**
 * DokuWiki Plugin imagecdn (Syntax Component)
 *
 * https://github.com/kabbala/dokuwiki-plugin-imagecdn/
 */

if (!defined('DOKU_INC')) { die(); }

class syntax_plugin_imagecdn extends DokuWiki_Syntax_Plugin
{

    public function getType()
    {
        return 'substition';
    }

    public function getPType()
    {
        return 'normal';
    }

    public function getSort()
    {
        return 319;    // just before Doku_Parser_Mode_media(320)
    }

    public function connectTo($mode)
    {
        $this->Lexer->addSpecialPattern("\{\{:[a-z0-9:_\-]+.(?:jpg|gif|png)(?:\?[a-z0-9&=]*)?(?:\|)?\}\}", $mode, 'plugin_imagecdn');
    }

    /**
     * Handle matches of the imagecdn syntax
     *
     * @param string       $match   The match of the syntax
     * @param int          $state   The state of the handler
     * @param int          $pos     The position in the document
     * @param Doku_Handler $handler The handler
     *
     * @return array Data for the renderer
     */
    public function handle($match, $state, $pos, Doku_Handler $handler)
    {
    
        $data = Doku_Handler_Parse_Media($match);
    
        if (!($data['type'] == 'internalmedia'))
        {
            // no rendering. error.
        }
        elseif (!trim($this->getConf('imagecdn_url')))
        {
            // same as normal internalmedia
        }
        elseif ($this->getConf('use_fetch')==0)
        {
            // direct link image without using fetch.php
            $match = trim($this->getConf('imagecdn_url')).str_replace(':', '/', substr($match,3,-2));
            $data = Doku_Handler_Parse_Media($match);
            $match = substr($match, 0, strrpos($match, '?'));
            
            if ($data['width']>0 && $data['height']>0)
            {
                $style = 'style="width: ' . $data['width'] . 'px; height: ' . $data['height'] . 'px;"';
            }
            elseif ($data['width']>0)
            {
                $style = 'style="width: ' . $data['width'] . 'px; height: auto;"';
            }
            elseif ($data['height']>0)
            {
                $style = 'style="width: auto; height: ' . $data['height'] . 'px;"';
            }
            
            if ($data['title']) { $title = 'title="' . $data['title'] . '"'; }

            $data[0] = '<img src="' . $match . '" ' . $style . ' ' . $title . '>';

        }
        else
        {
            // convert link
            $match = trim($this->getConf('imagecdn_url')).str_replace(':', '/', substr($match,3,-2));
            $data = Doku_Handler_Parse_Media($match);
            $data['cache'] = 'nocache';
            if ($this->getConf('default_nolink')==1)
            {
                // there is typo, detail and details.
                if $data['linking'] == 'details' or $data['linking'] == 'detail' or !$data['linking']) { $data['linking'] = 'nolink'; }
            } 
        }
        
        return $data;
         
    }

    /**
     * Render xhtml output or metadata
     *
     * @param string        $mode     Renderer mode (supported modes: xhtml)
     * @param Doku_Renderer $renderer The renderer
     * @param array         $data     The data from the handler() function
     *
     * @return bool If rendering was successful.
     */
    public function render($mode, Doku_Renderer $renderer, $data)
    {
        if ($mode !== 'xhtml') {
            return false;
        }

        if ($data['type']=='internalmedia' && !trim($this->getConf('imagecdn_url')))
        {
            $renderer->internalmedia($data['src'], $data['title'], $data['align'], $data['width'], $data['height'], $data['cache'], $data['linking'], false);

        }
        elseif ($data['type']=='externalmedia' && trim($this->getConf('imagecdn_url')) && $this->getConf('use_fetch')==1)
        {
            $renderer->doc .= $renderer->externalmedia($data['src'], $data['title'], $data['align'], $data['width'], $data['height'], $data['cache'], $data['linking'], false);
        }
        elseif ($data['type']=='externalmedia' && trim($this->getConf('imagecdn_url')) && $this->getConf('use_fetch')==0) 
        {
            $renderer->doc .= $data[0];
        }
        else
        {
        $renderer->doc .= $data['src'];    // error
        }

        return true;

    }
}
