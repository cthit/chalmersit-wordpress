# Require any additional compass plugins here.
require 'zurb-foundation'


ASSET_PATH = "assets"

# Set this to the root of your project when deployed:
http_path = "/"
css_dir = "."
sass_dir = File.join ASSET_PATH, "stylesheets"
images_dir = File.join ASSET_PATH, "images"

# You can select your preferred output style here (can be overridden via the command line):
output_style = (environment == :production) ? :compressed : :expanded

# To enable relative paths to assets via compass helper functions. Uncomment:
relative_assets = true

# To disable debugging comments that display the original location of your selectors. Uncomment:
line_comments = false

# Disable cache busting
asset_cache_buster :none