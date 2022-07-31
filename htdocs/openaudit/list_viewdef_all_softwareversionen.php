<?php
$query_array=array("headline"=>__("List all Software-Versions"),
                   "sql"=>"SELECT sv_datum,sv_rating,sv_id,sv_product,sv_version,sv_instlocation,sv_bemerkungen,sv_vorinstall,sv_quelle,sv_lizenztyp,sv_lizenzgeber,sv_lizenzbestimmungen,sv_herstellerwebsite FROM softwareversionen",
                   "sort"=>"sv_product",
                   "dir"=>"ASC",
                   "get"=>array("file"=>"list.php",
                                "title"=>__("Software-Versions"),
                                "var"=>array("name"=>"%sv_product",
                                             "version"=>"%sv_version",
                                             "view"=>"systems_for_software_version",
                                             "headline_addition"=>"%sv_product",
                                            ),
                               ),
                   "fields"=>array("10"=>array("name"=>"sv_id",
                                               "head"=>__("ID"),
                                               "show"=>"y",
                                               "link"=>"n",
                                               "sort"=>"y",
                                               "search"=>"n",
                                              ),
                                   "20"=>array("name"=>"sv_product",
                                               "head"=>__("Name"),
                                               "show"=>"y",
                                               "link"=>"y",
											   "search"=>"y",
											   "get"=>array("file"=>"list.php",
                                                            "title"=>__("Systems installed this Software"),
                                                            "var"=>array("name"=>"%sv_product",
                                                                         "view"=>"systems_for_software",
                                                                         "headline_addition"=>"%sv_product",
                                                                        ),
                                                           ),
                                              ),
                                   "30"=>array("name"=>"sv_version",
                                               "head"=>__("Version"),
                                               "show"=>"y",
                                               "link"=>"y",
                                              ),
								   "31"=>array("name"=>"sv_instlocation",
                                               "head"=>__("SCX"),
                                               "show"=>"y",
                                               "link"=>"n",
											   "sort"=>"y",
                                              ),

                                   "32"=>array("name"=>"sv_bemerkungen",
                                               "head"=>__("Bemerkungen"),
                                               "show"=>"y",
                                               "link"=>"n",
                                              ),

                                   "40"=>array("name"=>"sv_lizenztyp",
                                               "head"=>__("Lizenztyp"),
                                               "show"=>"y",
                                               "link"=>"y",
                                              ),
                                   "42"=>array("name"=>"sv_vorinstall",
                                               "head"=>__("Depot"),
                                               "show"=>"y",
                                               "link"=>"n",
                                              ),
                                   "44"=>array("name"=>"sv_herstellerwebsite",
                                               "head"=>__("Herstellerwebsite"),
                                               "show"=>"y",
                                               "link"=>"y",
                                               "get"=>array("file"=>"%sv_herstellerwebsite",
                                                            "title"=>__("External Link"),
                                                            "target"=>"_BLANK",
                                                           ),
                                              ),


							  ),
                  );
?>
