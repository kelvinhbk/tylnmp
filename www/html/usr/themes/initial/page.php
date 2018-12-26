<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>
<div id="main">
<?php if (!empty($this->options->Breadcrumbs) && in_array('Pageshow', $this->options->Breadcrumbs)): ?>
<div class="breadcrumbs">
<a href="<?php $this->options->siteUrl(); ?>">首页</a> &raquo; <?php $this->title() ?>
</div>
<?php endif; ?>
<article class="post">
<h1 class="post-title"><a href="<?php $this->permalink() ?>"><?php $this->title() ?></a></h1>
<div class="post-content">
<?php $this->content(); ?>
</div>
</article>
<p class="rewards">打赏: 
<?php if ($this->options->WeChat): ?>
<a><img src="<?php $this->options->WeChat(); ?>" alt="微信收款二维码" />微信</a>
<?php endif; if ($this->options->WeChat && $this->options->Alipay): ?>, <?php endif; if ($this->options->Alipay): ?>
<a><img src="<?php $this->options->Alipay(); ?>" alt="支付宝收款二维码" />支付宝</a>
<?php endif; ?>
</p>
<?php $this->need('comments.php'); ?>
</div>
<?php $this->need('sidebar.php'); ?>
<?php $this->need('footer.php'); ?>