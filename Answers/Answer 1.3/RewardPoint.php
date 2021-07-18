<?php 

namespace Kunyo\test;
class RewardPoint{

	private $totalPrice = 0;
	private $currency = 'USD';
	private $order = [];

	function _construct($totalPrice = 0,$currency = "USD"){
		$this->totalPrice = $totalPrice;
		$this->currency = $currency;
	}

	function _getRewardPoint()
	{
		
		// get the price to USD since the points is on the basis of the 
        $price = ($this->currency == 'USD')?$this->totalPrice:
        $this->priceToUsd($this->totalPrice);

        return $this->convertAmountToPoint($price);
	}

	// convert the given amount into equivalent point or vice versa;
	function convertAmountToPoint($amount,$type="amount")
	{
		$ratio = DB::table('settings')
        ->select('value')
        ->where('field', '=', 'reward_point')
        ->first();

        $ratio = (!empty($ratio->value) && $ratio->value > 0)?$ratio->value:0.01;
        return ($type == 'amount')?($amount*$ratio):($amount/$ratio);
	}


	// function to convert another currency to USD
	function priceToUsd($amount)
	{
		$convertedAmount = $amount;
		// i am not calling currency converter api in this test

		return $convertedAmount;
	}

	// function to save the reward uses history and debit the points from users rewards
	function saveRewardHistory($order = [],$points = 0)
	{
		if(!empty($order)){
			$this->order = $order;
		}
		if($points <= 0){
			return true;
		}
		
		$reward_amount = $this->convertAmountToPoint($points,'points');
		$customer_id = $this->order->customer_id;
		$total_amount = $this->order->total_amount;
		$order_id = $this->order->id;
		$data = [
            'customer_id' => $customer_id,
            'order_id' => $order_id,
            'date' => date("Y-m-d"),
            'reward_points' => $points,
            'reward_amounts' => $reward_amount,
        ];

        DB::table('reward_histories')->insert($data);

        $customer_reward =   DB::table('customer_rewards')
        ->select('points')
        ->where('customer_id', '=', $customer_id)
        ->where("expiry_date",">=",date("Y-m-d"))
        ->first();

        $remainingPoints = $customer_reward->points - $points;

        if($remainingPoints > 0){
        	 DB::table('customer_rewards')
	        ->where('customer_id', '=', $customer_id)
	        ->update(['points' => $remainingPoints]);
        }else{
        	DB::table('customer_rewards')
	        ->where('customer_id', '=', $customer_id)
	        ->delete();
        }
	}

	// function to credit the user reward point after completion of order
	function creditRewardPoint($order = [])
	{
		if(!empty($order)){
			$this->order = $order;
		}
		$todayDate = date("Y-m-d");
		$nextYear = Carbon::now()->addYear();
		$customer_id = $this->order->customer_id;
		$this->totalPrice = $this->order->total_amount;
		$newReward = $this->_getRewardPoint();
		$customer_reward =   DB::table('customer_rewards')
        ->select('points')
        ->where('customer_id', '=', $customer_id)
        ->where("expiry_date",">=",$todayDate)
        ->first();
        if(!empty($customer_reward)){
        	// if already have reward point then add to customers account
        	 DB::table('customer_rewards')
	        ->where('customer_id', '=', $customer_id)
	        ->where("expiry_date",">=",$todayDate)
	        ->update(['points' => $customer_reward + $newReward]);
        }else{
        	// if customer didnt have reward point then add to customers account
        	$data = [
            'customer_rewards' => $customer_id,
            'points' => $newReward,
            'expiry_date' => $nextYear
        	];

        	DB::table('reward_histories')->insert($data);
        }

	}
}