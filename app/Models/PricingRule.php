<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;
class PricingRule extends Model
{
    use HasFactory;
use LogsActivity;
     protected $guarded = [];

    protected $casts = [
        'base_rate' => 'decimal:2',
        'per_unit_rate' => 'decimal:2',
        'min_charge' => 'decimal:2',
        'max_charge' => 'decimal:2',
        'conditions' => 'array',
        'is_active' => 'boolean',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('rule_type', $type);
    }

    // Methods
    public function calculateCharge($units)
    {
        $charge = $this->base_rate + ($units * $this->per_unit_rate);

        if ($this->min_charge && $charge < $this->min_charge) {
            $charge = $this->min_charge;
        }

        if ($this->max_charge && $charge > $this->max_charge) {
            $charge = $this->max_charge;
        }

        return round($charge, 2);
    }

    public function meetsConditions($shipment)
    {
        if (!$this->conditions || empty($this->conditions)) {
            return true;
        }

        foreach ($this->conditions as $key => $value) {
            if (!$this->checkCondition($shipment, $key, $value)) {
                return false;
            }
        }

        return true;
    }

    protected function checkCondition($shipment, $key, $value)
    {
        switch ($key) {
            case 'min_weight':
                return $shipment->total_weight >= $value;
            case 'max_weight':
                return $shipment->total_weight <= $value;
            case 'min_distance':
                return $shipment->calculateDistance() >= $value;
            case 'max_distance':
                return $shipment->calculateDistance() <= $value;
            case 'shipment_type':
                return $shipment->shipment_type === $value;
            case 'delivery_priority':
                return $shipment->delivery_priority === $value;
            default:
                return true;
        }
    }

    // Static Methods
    public static function getApplicableRules($shipment, $type = null)
    {
        $query = self::active();

        if ($type) {
            $query->where('rule_type', $type);
        }

        return $query->get()->filter(function ($rule) use ($shipment) {
            return $rule->meetsConditions($shipment);
        });
    }

    public static function calculatePrice($shipment)
    {
        $totalPrice = 0;

        // Base price
        $baseRules = self::getApplicableRules($shipment, 'base');
        foreach ($baseRules as $rule) {
            $totalPrice += $rule->base_rate;
        }

        // Weight charge
        $weightRules = self::getApplicableRules($shipment, 'weight');
        foreach ($weightRules as $rule) {
            $totalPrice += $rule->calculateCharge($shipment->total_weight);
        }

        // Distance charge
        $distanceRules = self::getApplicableRules($shipment, 'distance');
        foreach ($distanceRules as $rule) {
            $totalPrice += $rule->calculateCharge($shipment->calculateDistance());
        }

        // Priority charge
        $priorityRules = self::getApplicableRules($shipment, 'priority');
        foreach ($priorityRules as $rule) {
            $totalPrice += $rule->base_rate;
        }

        // Service charges
        $serviceRules = self::getApplicableRules($shipment, 'service');
        foreach ($serviceRules as $rule) {
            $totalPrice += $rule->base_rate;
        }

        return round($totalPrice, 2);
    }
}