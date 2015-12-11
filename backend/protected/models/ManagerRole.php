<?php

class ManagerRole extends CActiveRecord {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return '{{manager_role}}';
	}

	public function attributeLabels() {
		return array(
				'manager_role_id' => '#编号', 
				'name' => '角色名称',
				'description' => '介绍', 
				'menu' => '菜单', 
				'privileges' => '权限',
				'is_admin' => '是否为管理员', 
				'create_time' => '添加时间', 
				'update_time' => '最后更新时间',
				'userCount' => '用户数'
		);
	}

	public function rules() {
		return array(
				array('name', 'required'),
				array('name, description, menu, privileges', 'safe'),
				array('is_admin', 'type', 'type' => 'boolean'),
				array('create_time, update_time', 'type', 'type' => 'date', 
						'dateFormat' => 'yyyy-MM-dd HH:mm:ss', 
						'allowEmpty' => true)
		);
	}

	public function relations() {
		return array(
				'managers' => array(self::HAS_MANY, 'Manager', 'manager_role_id'),
				'managerCount' => array(self::STAT, 'Manager', 'manager_role_id')
		);
	}

	protected function beforeSave() {
		if ($this->isNewRecord) {
			$this->create_time = new CDbExpression('NOW()');
		} else {
			$this->update_time = new CDbExpression('NOW()');
		}
		return true;
	}

	public static $privileges = array(
		array('label' => 'Banner管理', 'items' => array(
				array('label' => '查看', 'privilege' => 'viewBanner'),
				array('label' => '添加', 'privilege' => 'createBanner'),
				array('label' => '修改', 'privilege' => 'updateBanner'),
				array('label' => '删除', 'privilege' => 'deleteBanner')
		)),
		// array('label' => 'Banner位置', 'items' => array(
		// 		array('label' => '查看', 'privilege' => 'viewBannerPosition'),
		// 		array('label' => '添加', 'privilege' => 'createBannerPosition'),
		// 		array('label' => '修改', 'privilege' => 'updateBannerPosition'),
		// 		array('label' => '删除', 'privilege' => 'deleteBannerPosition')
		// )),
		array('label' => '产品中心', 'items' => array(
				array('label' => '查看', 'privilege' => 'viewProduct'),
				array('label' => '添加', 'privilege' => 'createProduct'),
				array('label' => '修改', 'privilege' => 'updateProduct'),
				array('label' => '删除', 'privilege' => 'deleteProduct')
		)),
		array('label' => '产品类型', 'items' => array(
				array('label' => '查看', 'privilege' => 'viewProductCategory'),
				array('label' => '添加', 'privilege' => 'createProductCategory'),
				array('label' => '修改', 'privilege' => 'updateProductCategory'),
				array('label' => '删除', 'privilege' => 'deleteProductCategory')
		)),
		array('label' => '产品系列', 'items' => array(
				array('label' => '查看', 'privilege' => 'viewProductSeries'),
				array('label' => '添加', 'privilege' => 'createProductSeries'),
				array('label' => '修改', 'privilege' => 'updateProductSeries'),
				array('label' => '删除', 'privilege' => 'deleteProductSeries')
		)),		
		array('label' => '问题咨询', 'items' => array(
				array('label' => '查看', 'privilege' => 'viewFeedback'),
				array('label' => '修改', 'privilege' => 'updateFeedback'),
				array('label' => '删除', 'privilege' => 'deleteFeedback')
		)),
		array('label' => '服务中心', 'items' => array(
				array('label' => '查看', 'privilege' => 'viewServer'),
				array('label' => '添加', 'privilege' => 'createServer'),
				array('label' => '修改', 'privilege' => 'updateServer'),
				array('label' => '删除', 'privilege' => 'deleteServer')
		)),
		array('label' => '问题回复', 'items' => array(
				array('label' => '查看', 'privilege' => 'viewAnswer'),
				array('label' => '添加', 'privilege' => 'createAnswer'),
				array('label' => '修改', 'privilege' => 'updateAnswer'),
				array('label' => '删除', 'privilege' => 'deleteAnswer')
		)),
		array('label' => '品牌中心', 'items' => array(
				array('label' => '查看', 'privilege' => 'viewBrand'),
				array('label' => '添加', 'privilege' => 'createBrand'),
				array('label' => '修改', 'privilege' => 'updateBrand'),
				array('label' => '删除', 'privilege' => 'deleteBrand')
		)),
		array('label' => '品牌地区', 'items' => array(
				array('label' => '查看', 'privilege' => 'viewRegion'),
				array('label' => '添加', 'privilege' => 'createRegion'),
				array('label' => '修改', 'privilege' => 'updateRegion'),
				array('label' => '删除', 'privilege' => 'deleteRegion')
		)),
		array('label' => '目录', 'items' => array(
				array('label' => '查看', 'privilege' => 'viewPage'),
				array('label' => '添加', 'privilege' => 'createPage'),
				array('label' => '修改', 'privilege' => 'updatePage'),
				array('label' => '删除', 'privilege' => 'deletePage')
		)),
		array('label' => '管理员', 'items' => array(
				array('label' => '查看', 'privilege' => 'viewManager'),
				array('label' => '添加', 'privilege' => 'createManager'),
				array('label' => '修改', 'privilege' => 'updateManager'),
				array('label' => '删除', 'privilege' => 'deleteManager')
		)),
		array('label' => '管理员角色', 'items' => array(
				array('label' => '查看', 'privilege' => 'viewManagerRole'),
				array('label' => '添加', 'privilege' => 'createManagerRole'),
				array('label' => '修改资料', 'privilege' => 'updateManagerRole'),
				array('label' => '修改权限', 'privilege' => 'updateManagerRolePrivilege'),
				array('label' => '删除', 'privilege' => 'deleteManagerRole')
		)),
		array('label' => '管理员操作日志', 'items' => array(
				array('label' => '查看', 'privilege' => 'viewManagerLog')
		)),
		array('label' => '其他', 'items' => array(
				array('label' => '系统设置', 'privilege' => 'updateSetting'),
				array('label' => '清除缓存', 'privilege' => 'cleanCache'),
				array('label' => '备份数据库记录', 'privilege' => 'exportSqlFile'),
		))
	);


	public static $defaultMenu = array(
		array(
				'label' => '首页', 
				'url' => array('site/index'),
				'privilege' => false, 
		),
		array(
				'label' => 'Banner管理', 
				'url' => array('banner/index'),
				'privilege' => 'viewBanner',
				'items' => array(
						array(
							'label' => 'Banner管理',
							'url' => array('banner/index'),
							'privilege' => 'viewBanner',
						),
						array(
							'label' => '添加Banner',
							'url' => array('banner/create'),
							'privilege' => 'createBanner',
						),
						// array(
						// 	'label' => 'Banner位置管理',
						// 	'url' => array('bannerPosition/index'),
						// 	'privilege' => 'viewBannerPosition',
						// ),
						// array(
						// 	'label' => '添加Banner位置',
						// 	'url' => array('bannerPosition/create'),
						// 	'privilege' => 'createBannerPosition',
						// ),
				)
		),
		array(
				'label' => '产品中心', 
				'url' => array('product/index'),
				'privilege' => 'viewProduct',
				'items' => array(
						array(
							'label' => '产品管理',
							'url' => array('product/index'),
							'privilege' => 'viewProduct',
						),
						array(
							'label' => '添加产品',
							'url' => array('product/create'),
							'privilege' => 'createProduct',
						),
						array(
							'label' => '产品类型管理',
							'url' => array('productCategory/index'),
							'privilege' => 'viewProductCategory',
						),
						array(
							'label' => '添加产品类型',
							'url' => array('productCategory/create'),
							'privilege' => 'createProductCategory',
						),
						array(
							'label' => '产品系列管理',
							'url' => array('productSeries/index'),
							'privilege' => 'viewProductSeries',
						),
						array(
							'label' => '添加产品系列',
							'url' => array('productSeries/create'),
							'privilege' => 'createProductSeries',
						),
				)
		),
		array(
				'label' => '品牌中心', 
				'url' => array('brand/index'),
				'privilege' => 'viewBrand',
				'items' => array(
						array(
							'label' => '品牌管理',
							'url' => array('brand/index'),
							'privilege' => 'viewBrand',
						),
						array(
							'label' => '添加品牌',
							'url' => array('brand/create'),
							'privilege' => 'createBrand',
						),
						array(
							'label' => '地区管理',
							'url' => array('region/index'),
							'privilege' => 'viewRegion',
						),
						array(
							'label' => '添加地区',
							'url' => array('region/create'),
							'privilege' => 'createRegion',
						),
				)
		),
		array(
				'label' => '服务中心', 
				'url' => array('server/index'),
				'privilege' => 'viewServer',
				'items' => array(
						array(
							'label' => '服务管理',
							'url' => array('server/index'),
							'privilege' => 'viewServer',
						),
						array(
							'label' => '添加服务',
							'url' => array('server/create'),
							'privilege' => 'createServer',
						)
				)
		),
		array(
				'label' => '问题咨询', 
				'url' => array('feedback/index'), 
				'privilege' => 'viewFeedback', 
				'items' => array(
						array(
							'label' => '咨询管理', 
							'url' => array('feedback/index'),
							'privilege' => 'viewFeedback'
						),
						array(
							'label' => '咨询回复', 
							'url' => array('answer/index'),
							'privilege' => 'viewAnswer'
						)
				)
		),
		// array(
		// 		'label' => '单页面管理', 
		// 		'url' => array('page/index'), 
		// 		'privilege' => 'viewPage', 
		// 		'items' => array()
		// ),
		array(
				'label' => '管理员', 
				'url' => array('manager/index'), 
				'privilege' => 'viewManager',
				'items' => array(
						array(
							'label' => '角色', 
							'url' => array('managerRole/index'),
							'privilege' => 'viewManagerRole'
						),
						array(
							'label' => '操作日志', 
							'url' => array('managerLog/index'),
							'privilege' => 'viewManagerLog'
						)
				)
		)
	);

	public static function getAllPrivilege() {
		$privileges = array();
		foreach (self::$privileges as $action) {
			foreach ($action['items'] as $item) {
				$privileges[$item['privilege']] = $item['label'];
			}
		}
		return $privileges;
	}

	public function getPrivilegeArray() {
		return explode(",", $this->privileges);
	}

	public function getMenuItems() {
		// 权限过滤
		$privileges = explode(",", $this->privileges);
		$menu = self::$defaultMenu;
		
		foreach ($menu as $i => $menuOption) {
			if ($menuOption['privilege'] != false 
					&&in_array($menuOption['privilege'], $privileges) == false) {
				unset($menu[$i]);
			} else if (isset($menuOption['items'])) {
				foreach ($menuOption['items'] as $j => $submenuOption) {
					if (in_array($submenuOption['privilege'], $privileges) == false) {
						unset($menu[$i]['items'][$j]);
					}
				}
			}
		}

		return $menu;
	}

	public function getOptions() {
		$managerRoles = self::model()->findAll(array('order' => 'name ASC'));
		return CHtml::listData($managerRoles, 'manager_role_id', 'name');
	}
}
?>