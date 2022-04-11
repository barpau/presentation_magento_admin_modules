<?php
    class Bp_Offer_Adminhtml_TabController
    extends Mage_Adminhtml_Controller_Action
    {
        
        public function pdfHeaderFooter($page) {
            //Header
            //$image = Zend_Pdf_Image::imageWithPath( Mage::getBaseDir('media') . "/gekkon_custom/offers_header_to_pdf.jpg");
            $image = Zend_Pdf_Image::imageWithPath( Mage::getBaseDir('media') . "/gekkon_custom/ZAPATI_EMAIL_2019.png");
            
            $imgWidthPts  = $image->getPixelWidth() * 72 / 96;
            $imgHeightPts = $image->getPixelHeight() * 72 / 96;
            $y1           = 790; //picture bottom
            $y2           = 830;
            $maxHeight    = $y2 - $y1;
            $maxWidth     = ($maxHeight/$imgHeightPts) * $imgWidthPts;
            //$x3           = $width/2 - $maxWidth/2 ;
            $page->drawImage($image, 5 , $y1, 5 + $maxWidth, $y2);
            
            //Footer
            $page->setFont($this->font_regular, 10);
            $page->setFillColor(Zend_Pdf_Color_Html::color('black'));
            $page->drawText('Gekkon International s.r.o. ', 80, 35,'UTF-8');
            
            $page->setFont($this->font_regular, 10);
            $page->drawText('|', 212, 35,'UTF-8');
            
            $page->setFont($this->font_regular, 10);
            $page->drawText('Hradišťská 407, 533 52 Pardubice-Polabiny', 220, 35,'UTF-8');
            
            $page->setFont($this->font_regular, 10);
            $page->drawText('|', 432, 35,'UTF-8');
            $page->setFont($this->font_regular, 10);
            $page->drawText('IČ 25930681', 442, 35,'UTF-8');
            
            $page->setFont($this->font_regular, 10);
            $page->setFillColor(Zend_Pdf_Color_Html::color('blue'));
            $page->drawText('www.gekkon.org', 80, 15,'UTF-8');
            
            $page->setFont($this->font_regular, 10);
            $page->drawText('www.vysokozdvizny-vozik.cz', 225, 15,'UTF-8');
            
            $page->setFont($this->font_regular, 10);
            $page->drawText('www.eulift.cz', 440, 15,'UTF-8');
            
            $page->setFillColor(Zend_Pdf_Color_Html::color('black'));
            
        }
        
        public function generatePDF()
        {
            //echo '1';exit;
            //print_r($_REQUEST);exit;
            
            //příprava dat k doplnění
            
            //Product image
            $id_offer = $this->getRequest()->getParam('id_offer');
            //print_r ($id_offer);exit;
            
            $offer = Mage::getModel('bp_offer/tab')->load($id_offer);
            $offer->getId();
            $parameters = explode(',',$offer->getData('parameters'));
            //print_r($parameters);exit;
            $id_product = $offer->getData('id_product');
            
            $product = Mage::getModel('catalog/product')->load($id_product);
            
            $id_customer = $offer->getData('id_customer_offer');
            $customer = Mage::getModel('customer/customer')->load($id_customer);
            
            $pdf = new Zend_Pdf();
            
            // Set font
            $this->font_regular = Zend_Pdf_Font::fontWithPath('app/code/local/Bp/Offer/Font/amble/Amble-Regular.ttf');
            $this->font_bold = Zend_Pdf_Font::fontWithPath('app/code/local/Bp/Offer/Font/amble/Amble-Bold.ttf');
            $this->font_bold_italic = Zend_Pdf_Font::fontWithPath('app/code/local/Bp/Offer/Font/amble/Amble-BoldItalic.ttf');
            
            ///////////////////////////////// PAGE 1 ///////////////////////////////////
            $page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4);
            $pdf->pages[] = $page;
            
            // Universal
            $width = $page->getWidth(); //595px
            $height = $page->getHeight(); //842px
            
            $x = 30; //levý okraj
            $y = 750; //print table row from page top – 100px
            
            //Insert Header Footer
            
            $this->pdfHeaderFooter($page);
            
            // Set color
            $page->setFillColor(Zend_Pdf_Color_Html::color('black'));
            
            //Body
            $page->setFont($this->font_regular, 10);
            $page->drawText('Datum: ' . date("d.m.Y, H:i") . ' hod.' . "               ".'Zákazník: ', $x + 10, $y,'UTF-8');
            //$page->drawText('Zákazník: ', 200, 670,'UTF-8');
            
            $customer_firstname = $customer->getData('firstname');
            $customer_lastname = $customer->getData('lastname');
            
            $page->setFont($this->font_bold, 11);
            $page->drawText($customer_firstname . " "  .$customer_lastname, $x + 240, $y,'UTF-8');
            
            // Set font size
            $page->setFont($this->font_bold, 20);
            // Draw text
            $title = 'CENOVÁ NABÍDKA';
            $title_capital_x = $width/2 - strlen($title)/2 * 11;
            $page->drawText($title, $title_capital_x, $y - 50, 'UTF-8');
            
            $page->setFont($this->font_bold, 15);
            
            $product = Mage::getModel('catalog/product')->load($id_product);
            
            $offer_name = $product->getResource()->getAttribute('offer_product_name')->getFrontend()->getValue($product);
            
            //print_r($name);die;
            
            if (isset($offer_name)){
                $name = $offer_name;
            } else {
                $name = $product->getName();
            }
            
            $width_middle = $width/2;
            $name_middle = strlen($name)/2 * 7;
            
            $x1 = $width_middle - $name_middle;
            // echo "$name $width_middle $name_middle $x";
            // die;
            $page->drawText($name, $x1, $y - 90,'UTF-8');
            
            $type = $offer->getData('subtitle');
            $type_middle = strlen($type)/2 * 7;
            $x2 = $width_middle - $type_middle;
            
            $page->drawText($type, $x2, $y - 130,'UTF-8');
            
            $file_name_pilot = $product->getResource()->getAttribute('offer_pilot_picture')->getFrontend()->getValue($product);
            
            if (isset($file_name_pilot) && $file_name_pilot!='no_selection'){
                $image = Zend_Pdf_Image::imageWithPath( Mage::getBaseDir('media') . '/catalog/product/'.$file_name_pilot);
                
                $imgWidthPts  = $image->getPixelWidth() * 72 / 96;
                $imgHeightPts = $image->getPixelHeight() * 72 / 96;
                $y1           = $y - 550; //spodní okraj obrázku
                $y2           = $y - 170;
                $maxHeight    = $y2 - $y1;
                $maxWidth     = ($maxHeight/$imgHeightPts) * $imgWidthPts;
                $x3           = $width/2 - $maxWidth/2 ;
                $page->drawImage($image, $x3, $y1, $x3 + $maxWidth, $y2);
            } else {
                Mage::getSingleton('core/session')->addError('Nebyl definován pilotní obrázek.'); //addError lze nahradit addNotice a výsledek bude zelený font
                return true;
            }
            
            $description = $product->getOfferMainDescription();
            
            $page->setFont($this->font_regular, 10);
            
            $line = 180;
            $textChunk = wordwrap($description, 100, "\n");
            foreach(explode("\n", $textChunk) as $textLine){
                if ($textLine!=='') {
                    $page->drawText(strip_tags(ltrim($textLine)), 80, $line, 'UTF-8');
                    $line -=12;
                }
            }
            
            ///////////////////////////////// PAGE 2 ///////////////////////////////
            $page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4);
            $pdf->pages[] = $page;
            
            $y = 770;
            
            //Header Footer
            
            $this->pdfHeaderFooter($page);
            
            //Body
            $page->setFont($this->font_bold_italic, 20);
            // Draw text
            $title = 'PARAMETRY';
            $title_capital_x = $width/2 - strlen($title)/2 * 11;
            $page->drawText($title, $title_capital_x, $y - 30, 'UTF-8');
            
            
            //Table header row
            $y_tab_param = $y - 50;
            $page->setFillColor(Zend_Pdf_Color_Html::color('#c8c8c8'));
            $page->drawRectangle($x, $y_tab_param, $width-$x, $y_tab_param - 30);
            
            //Header values
            
            
            $page->setFillColor(Zend_Pdf_Color_Html::color('#000000'));
            $page->setFont($this->font_bold, 16);
            $page->drawText('TYP VOZÍKU', $x + 10, $y_tab_param - 20, 'UTF-8');
            $page->drawText($name, $x + 130, $y_tab_param - 20, 'UTF-8');
            // line between the columns header
            $page->drawLine($x + 110, $y_tab_param, $x + 110, $y_tab_param - 30);
            
            
            //parametry z nastavení produktu
            
            
            $product = Mage::getModel('catalog/product')->load($id_product);
            $attributeModel = Mage::getModel('eav/entity_attribute');
            
            //print_r($product);die;
            //print_r($product['prop_sirka_voziku_mm']);die;
            
            foreach($product->getAttributes() as $attribute ){
                //print_r($attribute);
                //$prop_attributes = $attribute->getData('frontend_label');
                $offset = $n * 20;
                
                //setting the gray and white stripes in a table
                
                
                
                
                if ($attribute->getAttributeGroupId() == 33){
                    $prop_attribute_ids = $attribute->getData('attribute_id');
                    //print_r($prop_attribute_ids);
                    $t = $attributeModel->load($prop_attribute_ids);
                    
                    $attribute_name = $t->getData('frontend_label');
                    $attribute_value = $attribute->getFrontend()->getValue($product);
                    
                    if($attribute_value != 0){
                        if ($n % 2 == 0){
                            $page->setFillColor(Zend_Pdf_Color_Html::color('#ffffff'));
                            $page->drawRectangle($x, $y_tab_param - 30 - $offset,  $page->getWidth() - $x, $y_tab_param - 50 - $offset);
                            $page->setFillColor(Zend_Pdf_Color_Html::color('#000000'));
                            $page->setFont($this->font_bold, 10);
                            $page->drawText($attribute_name, $x + 10, $y_tab_param - 45 - $offset, 'UTF-8');
                            $page->drawText($attribute_value, $x + 430, $y_tab_param - 45 - $offset, 'UTF-8');
                        } else {
                            $page->setFillColor(Zend_Pdf_Color_Html::color('#f0f0f0'));
                            $page->drawRectangle($x, $y_tab_param - 30 - $offset, $page->getWidth() - $x, $y_tab_param - 50 - $offset);
                            $page->setFillColor(Zend_Pdf_Color_Html::color('#000000'));
                            $page->setFont($this->font_bold, 10);
                            $page->drawText($attribute_name, $x + 10, $y_tab_param - 45 - $offset, 'UTF-8');
                            $page->drawText($attribute_value, $x + 430, $y_tab_param - 45 - $offset, 'UTF-8');
                        }
                        
                        $page->drawLine($x + 420, $y_tab_param - 30 - $offset, $x + 420, $y_tab_param - 50 - $offset);
                        
                        $n++;
                    }
                }
            }
            
            $file_name_vykres = $product->getResource()->getAttribute('offer_vykres')->getFrontend()->getValue($product);
            
            if (isset($file_name_vykres) && $file_name_vykres!='no_selection'){
                
                $image = Zend_Pdf_Image::imageWithPath( Mage::getBaseDir('media') . '/catalog/product/'.$file_name_vykres);
                
                $imgWidthPts  = $image->getPixelWidth() * 72 / 96;
                $imgHeightPts = $image->getPixelHeight() * 72 / 96;
                $y1           = 60; //spodní okraj obrázku
                $y2           = 350;
                $maxHeight    = $y2 - $y1;
                $maxWidth     = ($maxHeight/$imgHeightPts) * $imgWidthPts;
                $x3           = $width/2 - $maxWidth/2 ;
                $page->drawImage($image, $x3, $y1, $x3 + $maxWidth, $y2);
            }
            
            ///////////////////////////////// PAGE 3 ///////////////////////////////
            $page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4);
            $pdf->pages[] = $page;
            
            //Header Footer
            
            $this->pdfHeaderFooter($page);
            
            $acc = $product->getOfferStAccessories();
            $array = explode("\n", $acc);
            
            $page->setFont($this->font_bold, 20);
            
            $title = 'STANDARDNÍ VYBAVENÍ';
            $title_capital_x = $width/2 - strlen($title)/2 * 11;
            $page->drawText($title, $title_capital_x, $y - 30, 'UTF-8');
            
            
            $page->setFont($this->font_regular, 12);
            $n = 0;
            foreach ($array as $key){
                $offset = $n * 14;
                
                $page->drawText($key, 50, $y - 50 - $offset, 'UTF-8');
                $n++;
                
            }
            
            
            $page->setFont($this->font_bold, 20);
            
            $title = 'SPECIFIKACE CENY';
            $title_capital_x = $width/2 - strlen($title)/2 * 11;
            $page->drawText($title, $title_capital_x, $y - 140, 'UTF-8');
            
            
            $page->setFillColor(Zend_Pdf_Color_Html::color('#f0f0f0'));
            $page->drawRectangle($x + 5, $y - 180, $page->getWidth()-$x - 5, $y - 160);
            $page->drawLine($x + 420, $y - 180, $x + 420, $y - 160);
            
            $page->setFont($this->font_bold, 10);
            $page->setFillColor(Zend_Pdf_Color_Html::color('red'));
            $page->drawText($product->getName() . ' bez DPH', $x + 10, $y - 175, 'UTF-8');
            
            $page->setFont($this->font_bold, 12);
            $price = $offer->getData('price');
            $price_format_czech = number_format($price, 2, ',', ' ');
            $page->drawText($price_format_czech . ' Kč', $x + 430, $y - 175, 'UTF-8');
            
            $page->setFont($this->font_bold, 10);
            
            //Table rows
            $n=0;
            $len = count($parameters);
            foreach ($parameters as $parameterId) {
                //if($parentId != 0){
                $parameter = Mage::getModel('bp_offer/parameter')->load($parameterId);
                $offset = $n * 20;
                //setting the gray and white stripes in a table
                if ($n % 2 == 0){
                    
                    $page->setFillColor(Zend_Pdf_Color_Html::color('#ffffff'));
                    $page->drawRectangle($x + 5, $y-180 - $offset,  $page->getWidth()-$x - 5, $y - 200 - $offset);
                } else {
                    //$page->setFillColor(Zend_Pdf_Color_Html::color('#fafafa'));
                    $page->setFillColor(Zend_Pdf_Color_Html::color('#f0f0f0'));
                    $page->drawRectangle($x + 5, $y - 180 - $offset, $page->getWidth()-$x - 5, $y - 200 - $offset);
                }
                $page->drawLine($x + 420, $y - 180 - $offset, $x + 420, $y - 200 - $offset);
                //$page->drawLine($x + 380, $y-30, $x + 380, $y - 390);
                $page->setFillColor(Zend_Pdf_Color_Html::color('#000000'));
                // print_r($offset);
                // die;
                $page->drawText($parameter->getName(), $x + 20,$y - 195 - $offset , 'UTF-8');
                
                $price = $parameter->getPrice();
                $price_format_czech = number_format($price, 2, ',', ' ');
                $page->drawText($price_format_czech . ' Kč', $x + 430,$y - 195 - $offset , 'UTF-8');
                $n++;
            }
            
            $offset = $len * 20;
            //discount rows
            $page->setFont($this->font_regular, 10);
            $dis1 = $offer->getData('discount_1');
            $dis1price = $offer->getData('discount_1_price');
            $dis2 = $offer->getData('discount_2');
            $dis2price = $offer->getData('discount_2_price');
            $dis3 = $offer->getData('discount_3');
            $dis3price = $offer->getData('discount_3_price');
            
            $y_dis = $y - 180 - $offset;
            if ($dis1price != 0) {
                
                $page->setFillColor(Zend_Pdf_Color_Html::color('white'));
                $page->drawRectangle($x + 5, $y_dis, $width-$x - 5, $y_dis - 20);
                $page->drawLine($x + 420, $y_dis, $x + 420, $y_dis - 20);
                $page->setFillColor(Zend_Pdf_Color_Html::color('black'));
                $page->drawText($dis1, $x + 20, $y_dis - 15, 'UTF-8');
                
                $price_format_czech = number_format($dis1price, 2, ',', ' ');
                $page->drawText($price_format_czech . ' Kč', $x + 430, $y_dis - 15, 'UTF-8');
            }
            if ($dis2price != 0) {
                
                $page->setFillColor(Zend_Pdf_Color_Html::color('#f0f0f0'));
                $page->drawRectangle($x + 5, $y_dis - 20, $page->getWidth()-$x - 5, $y_dis -40);
                $page->drawLine($x + 420, $y_dis - 20, $x + 420, $y_dis - 40);
                
                $page->setFillColor(Zend_Pdf_Color_Html::color('black'));
                $page->drawText($dis2, $x + 20, $y_dis - 35, 'UTF-8');
                $price_format_czech = number_format($dis2price, 2, ',', ' ');
                $page->drawText($price_format_czech . ' Kč', $x + 430, $y_dis - 35, 'UTF-8');
            }
            if ($dis3price != 0) {
                
                $page->setFillColor(Zend_Pdf_Color_Html::color('white'));
                $page->drawRectangle($x + 5, $y_dis - 40, $page->getWidth()-$x - 5, $y_dis - 60);
                $page->drawLine($x + 420, $y_dis - 40, $x + 420, $y_dis - 60);
                
                $page->setFillColor(Zend_Pdf_Color_Html::color('black'));
                $page->drawText($dis3, $x + 20, $y_dis - 55, 'UTF-8');
                $price_format_czech = number_format($dis3price, 2, ',', ' ');
                $page->drawText($price_format_czech . ' Kč', $x + 430, $y_dis - 55, 'UTF-8');
            }
            
            //note
            $page->setFont($this->font_bold, 10);
            
            $text = $offer->getData('note');
            $y_note =  $y_dis - 100;
            if ($text !=0 ){
                $page->drawText('Poznámka:',50, $y_note,'UTF-8');
                $page->setFont($this->font_regular, 10);
                $textChunk = wordwrap($text, 110, "\n");
                foreach(explode("\n", $textChunk) as $textLine){
                    if ($textLine!=='') {
                        $page->drawText(strip_tags(ltrim($textLine)), 110, $y_note, 'UTF-8');
                        $y_note -=12;
                    }
                }
            }
            
            $page->setFont($this->font_regular, 10);
            $page->setFillColor(Zend_Pdf_Color_Html::color('black'));
            $y_link = $y_note - 70;
            $page->drawText('Rychlý nákup na eshopu:',50, $y_link,'UTF-8');
            
            $page->setFont($this->font_regular, 10);
            $page->setFillColor(Zend_Pdf_Color_Html::color('blue'));
            $page->drawText('https://eulift.cz/vysokozdvizne-voziky/322-vysokozdvizny-vozik-cdd1025el.html',170, $y_link,'UTF-8');
            
            
            //picture left
            $file_name_left = $product->getResource()->getAttribute('offer_pic_left')->getFrontend()->getValue($product);
            
            if (isset($file_name_left) && $file_name_left!='no_selection'){
                $image = Zend_Pdf_Image::imageWithPath( Mage::getBaseDir('media') . '/catalog/product/'.$file_name_left);
                
                $imgWidthPts  = $image->getPixelWidth() * 72 / 96;
                $imgHeightPts = $image->getPixelHeight() * 72 / 96;
                $y1           = $y_link - 285; //spodní okraj obrázku
                $y2           = $y_link - 20;
                $maxHeight    = $y2 - $y1;
                $maxWidth     = ($maxHeight/$imgHeightPts) * $imgWidthPts;
                $x3           = ($width/4 - $maxWidth/2) + 10 ;
                $page->drawImage($image, $x3, $y1, $x3 + $maxWidth, $y2);
            }
            
            //picture right
            $file_name_right = $product->getResource()->getAttribute('offer_pic_right')->getFrontend()->getValue($product);
            
            if (isset($file_name_right) && $file_name_right!='no_selection'){
                $image = Zend_Pdf_Image::imageWithPath( Mage::getBaseDir('media') . '/catalog/product/'.$file_name_right);
                
                $imgWidthPts  = $image->getPixelWidth() * 72 / 96;
                $imgHeightPts = $image->getPixelHeight() * 72 / 96;
                $y1           = $y_link - 285; //spodní okraj obrázku
                $y2           = $y_link - 20;
                $maxHeight    = $y2 - $y1;
                $maxWidth     = ($maxHeight/$imgHeightPts) * $imgWidthPts;
                $x3           = ($width/4*3 - $maxWidth/2) - 10 ;
                $page->drawImage($image, $x3, $y1, $x3 + $maxWidth, $y2);
                
                
                //$page->drawImage($image, $x + 230, 100, $width - $x, 250);
            }
            
            
            ///////////////////////////////// PAGE 4 ///////////////////////////////
            $page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4);
            $pdf->pages[] = $page;
            
            //Header Footer
            $this->pdfHeaderFooter($page);
            //Body
            $page->setFont($this->font_bold, 20);
            // Draw text
            $page->drawText('DODACÍ, SERVISNÍ A PLATEBNÍ PODMÍNKY, ZÁRUKA', 50, $y - 30, 'UTF-8');
            
            $page->setFont($this->font_bold, 20);
            $y_table = $y - 35;
            foreach (array(1,2,3,4,5,6) as $n){
                $offset = $n * 20;
                
                if ($n % 2 !== 0){
                    
                    $page->setFillColor(Zend_Pdf_Color_Html::color('#f0f0f0'));
                    $page->drawRectangle($x, $y_table - $offset, $page->getWidth()-$x, $y_table - 20 - $offset);
                    
                } else {
                    $page->setFillColor(Zend_Pdf_Color_Html::color('#ffffff'));
                    $page->drawRectangle($x, $y_table - $offset, $width - $x, $y_table - 20 - $offset);
                    
                }
            }
            
            //$page->drawLine($x + 100, $y-40, $x + 100, $y - 60);
            $page->drawLine($x + 175, $y_table-40, $x + 175, $y_table - 140);
            
            $page->setFont($this->font_bold, 10);
            $page->setFillColor(Zend_Pdf_Color_Html::color('#000000'));
            $page->drawText('Vozíky jsou dodávány ve shodě s platnými ČS předpisy pro provoz paletových a vysokozdvižných vozíků', $x + 10, $y_table - 35, 'UTF-8');
            
            $page->setFont($this->font_bold, 11);
            $page->drawText('Dodací lhůta', $x + 10,$y_table - 55 , 'UTF-8');
            $page->drawText($offer->getData('delivery'), $x + 180,$y_table - 55 , 'UTF-8');
            
            $page->drawText('Platební podmínky (prodej)', $x + 10,$y_table - 75 , 'UTF-8');
            $page->drawText($offer->getData('payment'), $x + 180,$y_table - 75 , 'UTF-8');
            $page->drawText('Sevisní podmínky (prodej)', $x + 10,$y_table - 95 , 'UTF-8');
            $page->drawText($offer->getData('servis'), $x + 180,$y_table - 95 , 'UTF-8');
            $page->drawText('Záruční doba (prodej)', $x + 10,$y_table - 115 , 'UTF-8');
            $page->drawText($offer->getData('guarantee'), $x + 180,$y_table - 115 , 'UTF-8');
            $page->drawText('Platnost nabídky', $x + 10,$y_table - 135 , 'UTF-8');
            $page->drawText($offer->getData('validity'), $x + 180,$y_table - 135 , 'UTF-8');
            
            //Fleet picture
            $image = Zend_Pdf_Image::imageWithPath( Mage::getBaseDir('media') . '/gekkon_custom/offer_settings/fleet.png');
            
            $imgWidthPts  = $image->getPixelWidth() * 72 / 96;
            $imgHeightPts = $image->getPixelHeight() * 72 / 96;
            $y1           = $y_table - 305; //spodní okraj obrázku
            $y2           = $y_table - 155;
            $maxHeight    = $y2 - $y1;
            $maxWidth     = ($maxHeight/$imgHeightPts) * $imgWidthPts;
            $x3           = $width/2 - $maxWidth/2 ;
            $page->drawImage($image, $x3, $y1, $x3 + $maxWidth, $y2);
            
            $y_table2 = $y - 190;
            
            $page->setFont($this->font_bold, 15);
            //$page->setLineDashingPattern(Zend_Pdf_Page::LINE_DASHING_SOLID);
            $page->setFillColor(Zend_Pdf_Color_Html::color('#c8c8c8'));
            $page->drawRectangle($x, $y_table2 - 165, $page->getWidth()-$x, $y_table2 - 190);
            
            //Header values
            $page->setFillColor(Zend_Pdf_Color_Html::color('#000000'));
            $page->drawText('Nabídku vyhotovil', $x + 10, $y_table2 - 182, 'UTF-8');
            
            $page->setFont($this->font_bold, 12);
            
            foreach (array(1,2,3,4,5) as $n){
                
                $offset = $n * 20;
                
                if ($n % 2 !== 0){
                    
                    $page->setFillColor(Zend_Pdf_Color_Html::color('#f0f0f0'));
                    $page->drawRectangle($x, $y_table2 - 170 - $offset, $page->getWidth()-$x - 230, $y_table2 - 190 - $offset);
                    
                } else {
                    $page->setFillColor(Zend_Pdf_Color_Html::color('#cec2a7'));
                    $page->drawRectangle($x, $y_table2 - 170 - $offset, $page->getWidth()-$x - 230, $y_table2 - 190 - $offset);
                    
                }
            }
            
            $page->drawRectangle($x + 305, $y_table2 - 190, $page->getWidth()-$x, $y_table2 - 290, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
            
            $page->setFillColor(Zend_Pdf_Color_Html::color('#000000'));
            $user = Mage::getSingleton('admin/session');
            //$userEmail = $user->getUser()->getEmail();
            $salesman = $user->getUser()->getFirstname() . " ". $user->getUser()->getLastname();
            $phone = $user->getUser()->getPhone();
            
            
            $page->drawText($salesman, $x + 10, $y_table2 - 205, 'UTF-8');
            
            $page->drawLine($x + 175, $y_table2-190, $x + 175, $y_table2 - 270);
            
            $page->drawText('Gekkon International, s.r.o.', $x + 10, $y_table2 - 225, 'UTF-8');
            $page->drawText('Hradišťská 407', $x + 10, $y_table2 - 245, 'UTF-8');
            $page->drawText('Mobil.: '. $phone, $x + 185, $y_table2 - 245, 'UTF-8');
            $page->drawText('533 52 Pardubice', $x + 10, $y_table2 - 265, 'UTF-8');
            
            $page->setFont($this->font_regular, 12);
            $page->drawText('obchod@eulift.cz', $x + 185, $y_table2 - 265, 'UTF-8');
            $page->drawText('www.eulift.cz', $x + 130, $y_table2 - 285, 'UTF-8');
            
            $image = Zend_Pdf_Image::imageWithPath( Mage::getBaseDir('media') . "/gekkon_custom/gekkon_station.png");
            //                        x1, y1,  x2, y2
            $page->drawImage($image, $x + 306, $y_table2 - 289, $page->getWidth()-$x-1, $y_table2 - 200);
            
            //Advert picture
            $image = Zend_Pdf_Image::imageWithPath( Mage::getBaseDir('media') . '/gekkon_custom/offer_settings/advert.png');
            
            
            $imgWidthPts  = $image->getPixelWidth() * 72 / 96;
            $imgHeightPts = $image->getPixelHeight() * 72 / 96;
            $y1           = $y_table2 - 508; //spodní okraj obrázku
            $y2           = $y_table2 - 305;
            $maxHeight    = $y2 - $y1;
            $maxWidth     = ($maxHeight/$imgHeightPts) * $imgWidthPts;
            $x3           = $width/2 - $maxWidth/2 ;
            
            $page->drawImage($image, $x3, $y1, $x3 + $maxWidth, $y2);
            
            // Get PDF document as a string
            $pdfData = $pdf->render();
            header("Content-Disposition: inline; filename=offer.pdf");
            header("Content-type: application/x-pdf");
            echo $pdfData;
        }
    ?>
