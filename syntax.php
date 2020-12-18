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
    
        if (!trim($this->getConf('imagecdn_url'))) {return;}
        
        $params = Doku_Handler_Parse_Media($match);
        
        if ($params['type'] == 'internalmedia')
        {
            $match = str_replace(':', '/', substr($match,3,-2));

            if (substr($this->getConf('imagecdn_url'),-1,1)!='/') { $match = '/'.$match; }

            if ($this->getConf('use_fetch')==1) {
 
                $data[0] = '<img src="' . ml($this->getConf('imagecdn_url').$match, array('cache' => 'nocache'), true) . '">';
         
            } else {
        
                $data[0] = '<img src="' . $this->getConf('imagecdn_url').$match.'?'.$this->getConf('imagecdn_url_suffix') . '">';

            } 

        }
        else {return;}
        
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

