<?php
/**
 * 随机跳转一篇文章
 *
 * @package GoodLuck
 * @author Ryan
 * @version 1.0.3
 * @link http://blog.iplayloli.com/typecho-plugin-goodluck
 */
 class GoodLuck_Plugin implements Typecho_Plugin_Interface {
	 /**
	 * execute function.
	 *
	 * @access public
	 * @return void
	 */
	public function execute(){}
	 /**
	 * 激活插件方法,如果激活失败,直接抛出异常
	 *
	 * @access public
	 * @return String
	 * @throws Typecho_Plugin_Exception
	 */
	public static function activate() {
		Helper::addRoute('goodluck', '/goodluck', 'GoodLuck_Action', 'goodluck');
		return('插件已经成功激活!');
	}
	/**
	 * 禁用插件方法,如果禁用失败,直接抛出异常
	 *
	 * @static
	 * @access public
	 * @return String
	 * @throws Typecho_Plugin_Exception
	 */
	public static function deactivate() {
		Helper::removeRoute('goodluck');
	}
	/**
	 * 获取插件配置面板
	 *
	 * @access public
	 * @param Typecho_Widget_Helper_Form $form 配置面板
	 * @return void
	 */
	public static function config(Typecho_Widget_Helper_Form $form) {
	}
	/**
	 * 个人用户的配置面板
	 *
	 * @access public
	 * @param Typecho_Widget_Helper_Form $form
	 * @return void
	 */
	public static function personalConfig(Typecho_Widget_Helper_Form $form){}

}
