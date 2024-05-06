<?php
class PluginTicketgeneratorConfig extends CommonDBTM
{
   static protected $notable = true;
   static function getMenuName()
   {
      return __('ticketgenerator');
   }
   static function getMenuContent()
   {
      global $CFG_GLPI;
      $menu = array();
      $menu['title']   = __('Etiqueta menu', 'ticketgenerator');
      $menu['page']    = "/plugins/ticketgenerator/front/index.php";
      return $menu;
   }
   // add tabs
   function getTabNameForItem(CommonGLPI $item, $withtemplate = 0)
   {
      // add ticket tab
      switch (get_class($item)) {
         case 'Ticket':
            return array(1 => __('Gerar etiqueta', 'ticketgenerator'));
         default:
      }
   }
   static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0)
   {
      switch (get_class($item)) {
         case 'Ticket':
            $config = new self();
            $config->showFormDisplay();
            break;
      }
      return true;
   }
   // ticket tab
   function showFormDisplay()
   {
      global $CFG_GLPI, $DB;
      $ID = $_REQUEST['id'];

      echo "<head>";
      echo "<script type='text/javascript'>";
      echo "function setIframeSource() {";
      echo "var theSelect = document.getElementById('PageType');";
      echo "var theIframe = document.getElementById('OsIframe');";
      echo "var theUrl;";
      echo "theUrl = theSelect.options[theSelect.selectedIndex].value;";
      echo "theIframe.src = theUrl;";
      echo "}";
      echo "</script>";
      echo "</head>";
      echo "<body>";
      echo "<iframe id='OsIframe' src='/plugins/ticketgenerator/front/ticketgenerator_pdf.php?id=$ID' frameborder='0' marginwidth='0' marginheight='0' width='100%' height='700'></iframe>";
      echo "</body>";
   }
}
