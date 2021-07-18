<?php
/* data given by question 2 outputs is following 

SELECT COUNT(DISTINCT order.`Order_ID`) as Number_Of_Order,
                      SUM(CASE 
                          WHEN order.`Sales_Type` = "Normal" 
                          THEN prod.`Normal_price` 
                          ELSE prod.`Promotion_price`
                      END) AS Total_Sales_Amount
                      FROM `orders` as order
                      INNER JOIN `orders_products` AS prod
                      ON order.`Order_ID` =  prod.`Order_ID`;

                      */

//outputs :- Number_Of_Order => 6, Total_Sales_Amount => 3,545.97

// on the basis of table given in question 2 the total sum of the Total_Sales_Amount
$totalSum = 3545.97;

echo "Total Amount of GST for this order is ::"._getGST($totalSum);

function _getGST($totalSum)
{
	//since the MYR 5 included 6% of GST ,unit percent for GST
	$unitGST = 6/(100*5);

	$totalGST = $totalSum * $unitGST;
	return $totalGST;

	// for the current amount 3545.97 the gst becomes 42.55
}