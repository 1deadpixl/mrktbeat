<?php
// Load database connection info
include('../db.conf.php');

// Connect to the SQL database
$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

$markets = ["Biotechnology", "Software", "Clean Technology", "Health Care", "E-Commerce", "Mobile", "Enterprise Software", "Internet", "Advertising", "Semiconductors", "Hardware + Software", "Finance", "Curated Web", "Technology", "Games", "Web Hosting", "Analytics", "Security", "Transportation", "Health and Wellness", "Online Shopping", "Manufacturing", "Travel", "Storage", "Social Media", "Education", "Fashion", "Search", "Automotive", "SaaS", "Medical Devices", "Oil and Gas", "Solar", "Hospitality", "Messaging", "Wireless", "Telecommunications", "News", "Video", "Photography", "Cloud Computing", "Design", "Communities", "Nanotechnology", "Payments", "Big Data", "File Sharing", "Music", "Public Transportation", "Entertainment", "Communications Hardware", "Consulting", "Big Data Analytics", "Mobile Payments", "Credit", "Hotels", "Web Development", "Shopping", "Real Estate", "Pharmaceuticals", "Public Relations", "Networking", "Marketing Automation", "Cloud Data Services", "Business Services", "Databases", "Retail", "Publishing", "Human Resources", "Peer-to-Peer", "Hardware", "Mobile Security", "Television", "Marketplaces", "Consumer Electronics", "Chemicals", "Medical", "Financial Services", "Therapeutics", "Diabetes", "Web Tools", "Content", "Personal Finance", "Customer Service", "Video Streaming", "Optimization", "Construction", "Collaboration", "Agriculture", "Testing", "Enterprises", "Data Security", "Apps", "Virtualization", "Home & Garden", "Data Integration", "Sales and Marketing", "Home Automation", "Sports", "Oil and Gas", "Recreation", "Bio-Pharm", "Online Shopping", "Cloud-Based Music", "Internet", "Oil", "Financial Exchanges", "English-Speaking", "Transportation", "Diabetes", "Oil & Gas", "Telecommunications", "Content Creators", "Solar", "Storage", "Fleet Management", "Content Delivery", "Farming", "Batteries", "Chemicals", "Geospatial", "Smart Grid", "Communities", "Credit", "Lead Management", "File Sharing", "Distributed Generation", "Food Processing", "Medical Professionals", "Insurance", "Wind", "Dental", "Communications Hardware", "Medical Devices", "Shoes", "Groceries", "Information Security", "Technology", "Clean Technology", "Web CMS", "Hotels", "Identity", "Data Security", "B2B Express Delivery", "Brokers", "Data Centers", "Big Data Analytics", "Doctors", "Construction", "Navigation", "Lingerie", "Fantasy Sports", "Billing", "Group Buying", "Pharmaceuticals", "Semantic Search", "Home & Garden", "Wireless", "Data Center Infrastructure", "Automotive", "Therapeutics", "Energy Efficiency", "Recipes", "Biotechnology", "Energy", "Agriculture", "Coworking", "Health Care", "World Domination", "Mobile Payments", "Payments", "Data Integration", "Environmental Innovation", "Opinions", "Mobile Security", "Aerospace", "Tech Field Support", "Risk Management", "Diagnostics", "Game Mechanics", "Credit Cards", "Coffee", "Religion", "Fraud Detection", "Rapidly Expanding", "Hospitals", "Peer-to-Peer", "Web Hosting", "Testing", "Event Management", "Tablets", "Email Marketing", "Semiconductors", "Enterprise Resource Planning", "Shopping", "Call Center Automation", "Specialty Chemicals", "Public Transportation", "Consumer Electronics", "Oil and Gas", "Recreation", "Bio-Pharm", "Cloud-Based Music", "Online Shopping", "Internet", "Financial Exchanges", "Farming", "Oil", "English-Speaking", "Transportation", "Fleet Management", "Oil & Gas", "Telecommunications", "Diabetes", "Content Creators", "Storage", "Solar", "Content Delivery", "Medical Professionals", "Smart Grid", "Batteries", "B2B Express Delivery", "Chemicals", "Communities", "Geospatial", "Food Processing", "Credit", "Insurance", "File Sharing", "Lead Management", "Enterprise Resource Planning", "Distributed Generation", "Communications Hardware", "Groceries", "Web CMS", "Wind", "Technology", "Clean Technology", "Medical Devices", "Hotels", "Dental", "Big Data Analytics", "Shoes", "Information Security", "Identity", "World Domination", "Data Security", "Home & Garden", "Data Centers", "Brokers", "Data Center Infrastructure", "Doctors", "Automotive", "Credit Cards", "Construction", "Group Buying", "Fantasy Sports", "Opinions", "Energy Efficiency", "Billing", "Semantic Search", "Navigation", "Pharmaceuticals", "Lingerie", "Environmental Innovation", "Tech Field Support", "Wireless", "Energy", "Payments", "Mobile Payments", "Health Care", "Therapeutics", "Peer-to-Peer", "Biotechnology", "Agriculture", "Recipes", "Coffee", "Discounts", "Event Management", "Mobile Security", "Clean Energy", "Business Services", "Data Integration", "Fraud Detection", "Shopping", "Coworking", "Home Decor", "Risk Management", "Web Hosting", "Aerospace", "Public Transportation", "Diagnostics", "Game Mechanics", "Web Tools", "Email Marketing", "Religion", "Rapidly Expanding", "Hospitals", "Consumer Electronics"];


foreach ($markets as $market) {
	echo $market + "<br>";
	$sql_query =
	"Insert Into investments
		Select
		  *
		From
		  crunchbase.orig_investments
		Where
		  crunchbase.orig_investments.company_market = \"" . $market . "\"";

	$sql_result = $mysqli->query($sql_query) or die($mysqli->error.__LINE__);
}


// Close the database connection
$mysqli->close();

echo "Done."
?>