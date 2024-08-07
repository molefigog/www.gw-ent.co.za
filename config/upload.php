
<?php 
	return [
		"tempDir" => "uploads/temp/", 
		"import" => [
			"file_name_type" => "timestamp",
			"extensions" => "json,csv",
			"limit" => "10",
			"max_file_size" => "3",
			"return_full_path" => false,
			"filename_prefix" => "",
			"upload_dir" => "uploads/files/"
		],
		
		"image" => [
			"file_name_type" => "timestamp",
			"extensions" => "jpg,png,gif,jpeg",
			"limit" => 1,
			"max_file_size" => 3, //in mb
			"return_full_path" => false,
			"filename_prefix" => "",
			"upload_dir" => "storage/images",
			"image_resize" => [ 
				"small" => ["width" => 100, "height" => 100, "mode" => "cover"], 
				"medium" => ["width" => 480, "height" => 480, "mode" => "contain"], 
				"large" => ["width" => 1024, "height" => 760, "mode" => "contain"]
			],

		],

		"file" => [
			"file_name_type" => "original",
			"extensions" => "mp3,mp4,webm,wav,avi,mpg,mpeg",
			"limit" => 1,
			"max_file_size" => 13, //in mb
			"return_full_path" => false,
			"filename_prefix" => "",
			"upload_dir" => "storage",
			"image_resize" => [ 
				"small" => ["width" => 100, "height" => 100, "mode" => "cover"], 
				"medium" => ["width" => 480, "height" => 480, "mode" => "contain"], 
				"large" => ["width" => 1024, "height" => 760, "mode" => "contain"]
			],

		],

		"logo" => [
			"file_name_type" => "timestamp",
			"extensions" => "jpg,png,gif,jpeg",
			"limit" => 1,
			"max_file_size" => 3, //in mb
			"return_full_path" => false,
			"filename_prefix" => "",
			"upload_dir" => "storage/images",
			"image_resize" => [ 
				"small" => ["width" => 100, "height" => 100, "mode" => "cover"], 
				"medium" => ["width" => 480, "height" => 480, "mode" => "contain"], 
				"large" => ["width" => 1024, "height" => 760, "mode" => "contain"]
			],

		],

		"favicon" => [
			"file_name_type" => "timestamp",
			"extensions" => "jpg,png,gif,jpeg",
			"limit" => 1,
			"max_file_size" => 3, //in mb
			"return_full_path" => false,
			"filename_prefix" => "",
			"upload_dir" => "storage/images",
			"image_resize" => [ 
				"small" => ["width" => 100, "height" => 100, "mode" => "cover"], 
				"medium" => ["width" => 480, "height" => 480, "mode" => "contain"], 
				"large" => ["width" => 1024, "height" => 760, "mode" => "contain"]
			],

		],

		"avatar" => [
			"file_name_type" => "timestamp",
			"extensions" => "jpg,png,gif,jpeg",
			"limit" => 1,
			"max_file_size" => 3, //in mb
			"return_full_path" => false,
			"filename_prefix" => "",
			"upload_dir" => "storage/avatars",
			"image_resize" => [ 
				"small" => ["width" => 100, "height" => 100, "mode" => "cover"], 
				"medium" => ["width" => 480, "height" => 480, "mode" => "contain"], 
				"large" => ["width" => 1024, "height" => 760, "mode" => "contain"]
			],

		],

	];
