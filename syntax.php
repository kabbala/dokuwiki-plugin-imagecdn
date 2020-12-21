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
        $this->Lexer->addSpecialPattern("\{\{:[a-z0-9:_\-]+.(?:jpg|gif|png)\}\}", $mode, 'plugin_imagecdn');

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
    
        $params = Doku_Handler_Parse_Media($match);
    
        if (!($params['type'] == 'internalmedia'))
        {
            // no rendering. error.
            $data[0] = $params['src'];
            
        }
        elseif (!trim($this->getConf('imagecdn_url')))
        {
            // same as normal internalmedia
            $data[0] = '<img src="' . ml($params['src']) . '" debug="2">';

        }
        elseif ($this->getConf('use_fetch')==0)
        {
            // direct link image without using fetch.php
            $match = str_replace(':', '/', substr($match,3,-2));
            $data[0] = '<img src="' . trim($this->getConf('imagecdn_url')).$match.'?'.trim($this->getConf('imagecdn_url_suffix')) . '" debug="3">';

        }
        else
        {
            // convert link
            $match = str_replace(':', '/', substr($match,3,-2));
            $data[0] = '<img src="' . ml(trim($this->getConf('imagecdn_url')).$match, array('cache' => 'nocache'), true) . '" debug="4">';
    
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

        if (!trim($this->getConf('imagecdn_url'))) {return;}

        if ($mode !== 'xhtml') {
            return false;
        }

        $renderer->doc .= $data[0];

        return true;

    }
}

