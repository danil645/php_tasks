<?
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');
$APPLICATION->SetTitle("Главная");
?><div id="barba-wrapper">
	<div class="article-list">
		 <?$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"our_clients",
	Array(
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"ADD_SECTIONS_CHAIN" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"DISPLAY_DATE" => "N",
		"DISPLAY_NAME" => "N",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "N",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array("PREVIEW_PICTURE",""),
		"FILTER_NAME" => "",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "1",
		"IBLOCK_TYPE" => "content",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"INCLUDE_SUBSECTIONS" => "N",
		"MESSAGE_404" => "",
		"NEWS_COUNT" => "20",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "Новости",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"PREVIEW_TRUNCATE_LEN" => "",
		"PROPERTY_CODE" => array("CONTENT","TITLE","","",""),
		"SET_BROWSER_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SORT_BY1" => "ID",
		"SORT_BY2" => "",
		"SORT_ORDER1" => "ASC",
		"SORT_ORDER2" => "",
		"STRICT_SECTION_CHECK" => "N"
	)
);?> <a class="article-item article-list__item" href="for-individuals.html" data-anim="anim-3">
		<div class="article-item__background">
 <img src="/local/templates/myDirShop/assets/images/article-item-bg-6.jpg" data-src="xxxHTMLLINKxxx0.39186223192351520.41491856731872767xxx" alt="">
		</div>
		<div class="article-item__wrapper">
			<div class="article-item__title">
				 Для физических лиц
			</div>
			<div class="article-item__content">
				 Лучшие решения для вашего дома: быстрый интернет, доступное кабельное&nbsp;TV, удобный домашний телефон
			</div>
		</div>
 </a><a class="article-item article-list__item" href="#" data-anim="anim-3">
		<div class="article-item__background">
 <img src="local/templates/myDirShop/assets/images/article-item-bg-3.jpg" data-src="xxxHTMLLINKxxx0.153709056148504830.8920151245249737xxx" alt="">
		</div>
		<div class="article-item__wrapper">
			<div class="article-item__title">
				 Средний и малый бизнес
			</div>
			<div class="article-item__content">
				 Быстро и&nbsp;качественно помогаем предпринимателям в&nbsp;решении бизнес-задач
			</div>
		</div>
 </a><a class="article-item article-list__item" href="for-state.html" data-anim="anim-3">
		<div class="article-item__background">
 <img src="local/templates/myDirShop/assets/images/article-item-bg-4.jpg" data-src="xxxHTMLLINKxxx0.83331501539025420.9635873669140569xxx" alt="">
		</div>
		<div class="article-item__wrapper">
			<div class="article-item__title">
				 Государственные заказчики
			</div>
			<div class="article-item__content">
				 Решения для государственных структур, повышение безопасности и&nbsp;комфорта городской среды
			</div>
		</div>
 </a><a class="article-item article-list__item" href="for-federals.html" data-anim="anim-3">
		<div class="article-item__background">
 <img src="/local/templates/myDirShop/assets/images/article-item-bg-5.jpg" data-src="xxxHTMLLINKxxx0.274858315149753230.570917169144997xxx" alt="">
		</div>
		<div class="article-item__wrapper">
			<div class="article-item__title">
				 Федеральные клиенты
			</div>
			<div class="article-item__content">
				 Повышаем эффективность бизнес-процессов за&nbsp;счет внедрения современных средств передачи и&nbsp;защиты данных
			</div>
		</div>
 </a><a class="article-item article-list__item" href="for-telecommunications.html" data-anim="anim-3">
		<div class="article-item__background">
 <img src="/local/templates/myDirShop/assets/images/article-item-bg-2.jpg" data-src="xxxHTMLLINKxxx0.4314468597192560.505419651272456xxx" alt="">
		</div>
		<div class="article-item__wrapper">
			<div class="article-item__title">
				 Операторы связи
			</div>
			<div class="article-item__content">
				 Предлагаем партнерство и&nbsp;взаимовыгодное сотрудничество
			</div>
		</div>
 </a><a class="article-item article-list__item" href="innovative-projects.html" data-anim="anim-3">
		<div class="article-item__background">
 <img src="/local/templates/myDirShop/assets/images/article-item-bg-1.jpg" data-src="xxxHTMLLINKxxx0.2544727135416540.7321213588928357xxx" alt="">
		</div>
		<div class="article-item__wrapper">
			<div class="article-item__title">
				 Инновационные проекты
			</div>
			<div class="article-item__content">
				 Предоставляем услуги широкополосного доступа в&nbsp;интернет и&nbsp;комплексные решения на&nbsp;базе технологий промышленного интернета вещей (IoT)
			</div>
		</div>
 </a>
	</div>
</div>
 <br><?
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');
?>