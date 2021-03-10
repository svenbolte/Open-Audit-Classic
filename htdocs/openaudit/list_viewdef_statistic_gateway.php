<?php
    $query_array=array("headline"=>__("Statistic for Gateway"),
                       "sql"=>"SELECT net.net_gateway,net.net_ip_subnet,net.net_dns_domain, net_manufacturer,net.net_connection_id,net_speed, net_description, COUNT( * ) AS count_item,
                                      ROUND( 100 / (SELECT count(*)
                                                    FROM network_card net, system sys
                                                    WHERE net.net_uuid = sys.system_uuid AND net.net_timestamp = sys.system_timestamp
                                                          AND net.net_gateway != '' AND (net.net_ip_address != 'none'
                                                                                         OR net.net_ip_address_2 != 'none'
                                                                                         OR net.net_ip_address_3 != 'none')
                                                     ) * COUNT( * ), $round_to_decimal_places) AS percentage
                               FROM network_card net, system sys
                               WHERE net.net_uuid = sys.system_uuid AND net.net_timestamp = sys.system_timestamp
                                     AND net.net_gateway != '' AND (net.net_ip_address != 'none' OR net.net_ip_address_2 != 'none'
                                                                    OR net.net_ip_address_3 != 'none')
                               GROUP BY net.net_gateway ",
                       "sort"=>"count_item",
                       "dir"=>"DESC",
                       "get"=>array("file"=>"list.php",
                                    "title"=>__("Hosts with this Gateway"),
                                    "var"=>array("view"=>"systems_for_gateway",
                                                 "headline_addition"=>"%net_gateway",
                                                ),
                                   ),
                       "fields"=>array("10"=>array("name"=>"net_gateway",
                                                   "head"=>__("Gateway"),
                                                   "show"=>"y",
                                                   "link"=>"y",
                                                  ),
									  "12"=>array("name"=>"net_ip_subnet",
											   "head"=>__("Subnet"),
											   "show"=>"y",
											   "link"=>"n",
											  ),
									  "14"=>array("name"=>"net_dns_domain",
											   "head"=>__("DNSDomain"),
											   "show"=>"y",
											   "link"=>"n",
											  ),
									  "16"=>array("name"=>"net_manufacturer",
											   "head"=>__("Hersteller"),
											   "show"=>"y",
											   "link"=>"n",
											  ),
									  "17"=>array("name"=>"net_connection_id",
											   "head"=>__("Verbindung"),
											   "show"=>"y",
											   "link"=>"n",
											  ),
									  "17"=>array("name"=>"net_connection_id",
											   "head"=>__("Verbindung"),
											   "show"=>"y",
											   "link"=>"n",
											  ),
									  "18"=>array("name"=>"net_speed",
											   "head"=>__("Speed MBit"),
											   "show"=>"y",
											   "link"=>"n",
											  ),
									  "18"=>array("name"=>"net_description",
											   "head"=>__("Beschreibung"),
											   "show"=>"y",
											   "link"=>"n",
											  ),
                                       "20"=>array("name"=>"count_item",
                                                   "head"=>__("Count"),
                                                   "show"=>"y",
                                                   "link"=>"y",
                                                   "search"=>"n",
                                                  ),
                                       "30"=>array("name"=>"percentage",
                                                   "head"=>__("Percentage"),
                                                   "show"=>"y",
                                                   "link"=>"n",
                                                   "search"=>"n",
                                                  ),
                                      ),
                      );
?>