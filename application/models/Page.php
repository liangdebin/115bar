<?php

/**
 * 获取和修改用户相关信息
 *
 */
class PageModel{
    private $total;      //总记录
    private $pagesize;    //每页显示多少条
    private $page;           //当前页码
    private $pagenum;      //总页码
    private $url;           //地址f
    private $bothnum;      //两边保持数字分页的量

    //构造方法初始化
    public function __construct($_url,$_total, $_page,$_pagesize,$_sep='?page=') {
       $this->total = $_total ? $_total : 1;
       $this->pagesize = $_pagesize;
       $this->pagenum = ceil($this->total / $this->pagesize);
       $this->page = $_page;
       $this->url = $_url;
       $this->bothnum = 3;
       $this->sep = $_sep;
    }

    //拦截器
    public function __get($_key) {
       return $this->$_key;
    }

    //数字目录   
    private function pageList() {
        $_pagelist = '';
        for ($i=$this->bothnum;$i>=1;$i--) {
           $_page = $this->page-$i;
           if ($_page < 1) continue;
           $_pagelist .= ' <a href="'.$this->url.$this->sep.$_page.'">'.$_page.'</a> ';
        }
        $_pagelist .= ' <span class="me">'.$this->page.'</span> ';
        for ($i=1;$i<=$this->bothnum;$i++) {
           $_page = $this->page+$i;
           if ($_page > $this->pagenum) break;
           $_pagelist .= ' <a href="'.$this->url.$this->sep.$_page.'">'.$_page.'</a> ';
        }   
        return $_pagelist;
    }

    //首页   
    private function first() {
        if ($this->page > $this->bothnum+1) {
               return ' <a href="'.$this->url.'">1</a> ...';
        }
    }

    //上一页   
    private function prev() {   
        if ($this->page == 1) {   
            return '<span class="disabled">prev</span>';
        }   
        return ' <a href="'.$this->url.$this->sep.($this->page-1).'">prev</a> ';
    }

    private function next() {   
      if ($this->page == $this->pagenum) {   
        return '<span class="disabled">next</span>';   
      }   
      return ' <a href="'.$this->url.$this->sep.($this->page+1).'">next</a> ';   
    }

    //尾页
    private function last() {
        if ($this->pagenum - $this->page > $this->bothnum) {   
            return ' ...<a href="'.$this->url.$this->sep.$this->pagenum.'">'.$this->pagenum.'</a> ';   
        }
    }

    //分页信息
    public function showpage() {
        $_page = '<ul class="am-pagination am-pagination-centered">';
        $_page .= $this->prev();
        $_page .= $this->first();
        $_page .= $this->pageList();
        $_page .= $this->last();
        $_page .= $this->next();
        $_page .= '</ul>';
        return $_page;
    }
}