<?php

namespace app\components;

use app\helpers\AppLocale;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Base application Model
 * BaseModel extend yii\db\ActiveRecord
 * @author vietvt <vietvotrung@admicro.vn>
 */
class Model extends ActiveRecord
{

    private static $_instance;

    /**
     * Determine deleted_f state of deleted_f
     */
    const DELETED = 1;

    /**
     * Determine note deleted state of deleted_f
     */
    const NOT_DELETED = 0;

    /**
     * Allow auto set value for attribute
     * @var type 
     */
    public $allowAutoSet = true;

    /**
     * Get instance of model
     * @return static model
     */
    public static function getInstance()
    {
        if (self::$_instance instanceof static) {
            return self::$_instance;
        }

        self::$_instance = new static;

        return self::$_instance;
    }

    /**
     * Add filter daterange for field. For mat value: Field[from_date,to_date], date format: d-m-Y
     * @param ActiveQuery $query
     * @param string $field model attribute name
     */
    protected function _addFilterDateRange(ActiveQuery $query, $field, $format = 'd-m-Y')
    {
        if (!empty($this->{$field}['from_date'])) {
            $query->andFilterWhere(['>=', $this->tableName() . ".$field", AppLocale::getTimeOfDate($this->{$field}['from_date'], $format)]);
        }

        if (!empty($this->{$field}['to_date'])) {
            $todate = AppLocale::getTimeOfDate($this->{$field}['to_date'], $format);
            $query->andFilterWhere(['<=', $this->tableName() . ".$field", $todate + 86399]); //add tim 23:59:59
        }
    }

    /**
     * Check new value is dirty
     * @param type $name
     * @param type $newValue
     * @return boolean
     */
    protected function _isChangedValue($name, $newValue)
    {
        $existingValue = $this->getOldAttribute($name);

        if ($existingValue === null) {
            return true;
        }

        if (!is_scalar($newValue) || !is_scalar($existingValue) || strval($newValue) !== strval($existingValue)) {
            return $newValue !== $existingValue;
        }

        return false;
    }

    /**
     * @todo OVERWRITE function getDirtyAttributes of BaseActiveRecord
     * @see \yii\db\BaseActiveRecord
     * Returns the attribute values that have been modified since they are loaded or saved most recently.
     *
     * The comparison of new and old values is made for identical values using `===`.
     *
     * @param string[]|null $names the names of the attributes whose values may be returned if they are
     * changed recently. If null, [[attributes()]] will be used.
     * @return array the changed attribute values (name-value pairs)
     */
    public function getDirtyAttributes($names = null)
    {
        $attributes = parent::getDirtyAttributes($names);

        if ($this->getOldAttributes() !== null) {
            foreach ($attributes as $name => $value) {
                if ($value === null || !$this->_isChangedValue($name, $value)) {
                    unset($attributes[$name]);
                }
            }
        }

        return $attributes;
    }

}
