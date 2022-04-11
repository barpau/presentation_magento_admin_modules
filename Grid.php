<?php
    class Bp_Service_Block_Adminhtml_Equipment_Grid
    extends Mage_Adminhtml_Block_Widget_Grid
    {
        
        protected function _getCollectionClass()
        {
            
            return 'bp_service/equipment_collection';
        }
        
        protected function _prepareCollection()
        {
            
            $collection = Mage::getResourceModel($this->_getCollectionClass());
            $this->setCollection($collection);
            
            return parent::_prepareCollection();
        }
        
        protected function _getLocation()
        {
            $collection = Mage::getModel('bp_service/location')->getCollection();
            $data_array=array();
            foreach($collection as $item) {
                $data_array[]=array('value'=>$item['id_location'],'label'=>$item['name']);
            }
            return($data_array);
            
        }
        
        protected function _getCompany()
        {
            $collection = Mage::getModel('bp_service/company')->getCollection();
            $data_array=array();
            foreach($collection as $item) {
                $data_array[]=array('value'=>$item['id_company'],'label'=>$item['name']);
            }
            return($data_array);
            
        }
        
        protected function _toOptions($optArray){
            $options=array();
            foreach ($optArray as $option){
                $options[$option['value']] = $option['label'];
            }
            return $options;
        }
        
        protected function _prepareColumns()
        {
            
            $this->addColumn('id_equipment',
                             array(
                                   'header'=> $this->__('ID'),
                                   'align' =>'right',
                                   'width' => '50px',
                                   'index' => 'id_equipment'
                                   )
                             );
            $this->addColumn('name',
                             array(
                                   'header'=> $this->__('Name'),
                                   'index' => 'name',
                                   )
                             );
            $this->addColumn('service_equipment_serial_number',
                             array(
                                   'header'=> $this->__('Serial number'),
                                   'index' => 'service_equipment_serial_number',
                                   'column_css_class'=> 'bold'
                                   )
                             );
            $this->addColumn('owner',
                             array(
                                   'header'=> $this->__('Owner'),
                                   'index' => 'owner'
                                   )
                             );
            $this->addColumn('service_company_id_company',
                             array(
                                   'header'=> $this->__('Company'),
                                   'index' => 'service_company_id_company',
                                   'type' => 'options',
                                   'options' => $this->_toOptions($this->_getCompany())
                                   )
                             );
            $this->addColumn('service_location_id_location',
                             array(
                                   'header'=> $this->__('Location'),
                                   'index' => 'service_location_id_location',
                                   'type' => 'options',
                                   'options' => $this->_toOptions($this->_getLocation())
                                   )
                             );
            $this->addColumn('branch_office',
                             array(
                                   'header'=> $this->__('Branch office'),
                                   'index' => 'branch_office'
                                   )
                             );
            $this->addColumn('rent_sale',
                             array(
                                   'header'=> $this->__('Rent/Sale'),
                                   'index' => 'rent_sale',
                                   'type'=>'options',
                                   'options'=> array('1'=>$this->__('Sale'), '2'=>$this->__('Rent Fixed-term'),'3'=>$this->__('Rent Indefinite-term'),'4'=>$this->__('Rent Operative'),'5'=>$this->__('Rent with Service'),'6'=>$this->__('Rent without Service'),)
                                   )
                             );
            $this->addColumn('service_period',
                             array(
                                   'header'=> $this->__('Service period'),
                                   'index' => 'service_period',
                                   'type'=>'options',
                                   'options'=> array('1'=>$this->__('1 month'), '3'=>$this->__('3 months'),'6'=>$this->__('6 months'),'12'=>$this->__('1 year') )
                                   )
                             );
            $this->addColumn('delivered',
                             array(
                                   'header'=> $this->__('Delivered'),
                                   'index' => 'delivered',
                                   'column_css_class'=>'no-display',
                                   'header_css_class'=>'no-display'
                                   )
                             );
            $this->addColumn('send_notice',
                             array(
                                   'header'=> $this->__('Send notice'),
                                   'index' => 'send_notice'
                                   )
                             );
            $this->addColumn('model_year',
                             array(
                                   'header'=> $this->__('Model year'),
                                   'index' => 'model_year'
                                   )
                             );
            $this->addColumn('notes',
                             array(
                                   'header'=> $this->__('Notes'),
                                   'index' => 'notes',
                                   'column_css_class'=>'no-display',
                                   'header_css_class'=>'no-display'
                                   )
                             );
            
            return parent::_prepareColumns();
        }
    }
