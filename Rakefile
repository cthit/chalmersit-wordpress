# Rake tasks for Chalmers.it Wordpress theme
# 
# Does things like JS concatenation and minification and CSS compiling. 
# 
# Remember to add any new JS files to the JS_FILES array below.

require 'rake'
require 'tempfile'
require 'yaml'

config = YAML.load_file 'environment.yml'

stage_branch = config['environments']['staging']['branch']
production_branch = config['environments']['live']['branch']

JS_FILES = config['environments']['staging']['javascripts']
MASTER_JS_FILE = config['environments']['live']['javascripts'].first
JS_DIR = 'assets/javascripts'

desc "Deploy to staging"
task :stage do
	commands = [
		"git stash", 
		"git checkout #{stage_branch}", 
		"git merge master", 
		"git push origin #{stage_branch}",
		"git checkout master",
		"git stash pop"
	]

	puts `#{commands.join(" && ")}`
	puts "* Deployed to staging (#{config['environments']['staging']['url']})".green
end

desc "Deploy to production"
task :deploy => ["css:all", "javascript:all"] do
	if current_branch != stage_branch
		puts "ERROR: ".red + "Cannot deploy from other branches than #{stage_branch}!\nAborting push ..."
		exit
	end

	commit_msg = config['environments']['live']['commit_message']

	commands = [
		"git add assets/css/style.css #{File.join(JS_DIR, MASTER_JS_FILE+".min.js")}",
		"git commit assets/css/style.css #{File.join(JS_DIR, MASTER_JS_FILE+".min.js")} -m '#{commit_msg}'",
		"git stash",
		"git checkout #{production_branch}", 
		"git merge #{stage_branch}", 
		"git push origin #{production_branch}",
		"git checkout #{stage_branch}",
		"git stash pop"
	]

	puts `#{commands.join(" && ")}`
	puts "* Deployed to production (#{config['environments']['live']['url']})".green
end

desc "Remove all generated CSS and Javascript files"
task :clean => ['css:clean', 'javascript:clean'] do
	puts "* Cleaned all files".yellow
end

desc "Generate all js+css files"
task :all => ['clean', 'javascript:all', 'css:all']

namespace :css do

	desc "Remove generated files"
	task :clean do
		puts `rm -rf assets/css/style.css`
		puts `rm -rf ./style.css`
	end

	desc "Compile, minify, and add header to CSS"
	task :all => [:minify] do
	end

	desc "Compile SCSS to CSS for development mode"
	task :compile do
		puts `compass compile --force`
		puts "* SCSS compiled to CSS".yellow
		add_wp_header "style.css"
	end

	desc "Compile SCSS to minified CSS for production mode"
	task :minify do
		puts `compass compile -e production --force`
		puts "* SCSS minified to CSS".yellow
		add_wp_header "style.css"
	end

end

namespace :javascript do


	desc "Concatenate Javascript files and minify into a single file"
	task :all => [:clean, :concat, :minify] do
	end


	desc "Remove generated Javascript files"
	task :clean do
		puts `rm -rf #{File.join(JS_DIR, "all.min.js")}`
		puts `rm -rf #{File.join(JS_DIR, "all.js")}`
	end

	desc "Strip trailing whitespace and ensure each file ends with newline"
	task :whitespace do
		Dir[JS_DIR + '*/**'].each do |filename|
			normalize_whitespace(filename) if File.file?(filename)
		end

		puts "* Normalized whitespace".yellow
	end

	desc "Concatenate all Javascript files"
	task :concat, [:filename] => :whitespace do |task, args|
		title = args[:filename].nil? ? "#{MASTER_JS_FILE}.js" : "#{args[:filename]}.js"

		File.open(File.join(JS_DIR, title), 'w') do |f|
			JS_FILES.map do |component|
				puts "Reading #{component}.js ..."
				f.puts File.read(File.join(JS_DIR, "#{component}.js"))
			end
		end

		puts "* Mashed together all files into '#{title}'".yellow
	end

	desc "Generate a minified version for distribution"
	task :minify, [:filename, :minifier] do |task, args|
		js_file = args[:filename].nil? ? "#{MASTER_JS_FILE}.js" : "#{args[:filename]}.js"
		minifier = args[:minifier].nil? ? "uglifier" : args[:minifier]

		src, target = File.join(JS_DIR, js_file), File.join(JS_DIR, output_filename(js_file))
		
		if minifier == "closure"
			google_compiler src, target
		elsif minifier == "uglifier"
			uglifyjs src, target
		end

		add_js_header target
	end
end


# Helpers

def current_branch
	branch = `git branch`.split("\n").map { |i| i.strip }.delete_if {|i| i.chars.first != "*"}
	branch.first.delete("*").strip
end

def add_wp_header(file)
  header = <<-HTML
/*
  Theme Name: Chalmers.it
  Theme URI: http://chalmers.it
  Author: Johan Brook (digIT 11/12)
  Author URI: http://johanbrook.com
  Version: 1.0
*/

HTML
  
	File.prepend file, header
end


def add_js_header(file)
	header = <<-HTML
/*
	Javascripts for Chalmers.it
	--------------------------------
	Files: [#{JS_FILES.join(", ")}]
	Build date: #{Time.now.strftime "%Y-%m-%d %H:%M"}
 */

HTML

	File.prepend file, header
end


# /javascript/application.js => /javascript/application.min.js
def output_filename(js_file)
  output_file = File.basename(js_file, File.extname(js_file))
  output_file = File.join(File.dirname(js_file), output_file)
  return output_file + ".min" + File.extname(js_file)
end


def normalize_whitespace(filename)
  contents = File.readlines(filename)
  contents.each { |line| line.sub!(/\s+$/, "") }
  File.open(filename, "w") do |file|
    file.write contents.join("\n").sub(/(\n+)?\Z/m, "\n")
  end
end

# Minify JS with Google's Closure compiler

def google_compiler(src, target)
  puts "Minifying #{src} with Google Closure Compiler...".pink
  `java -jar #{JS_DIR}/compressors/google-compiler/compiler.jar --js #{src} --summary_detail_level 3 --js_output_file #{target}`
end

# Minify JS with UglifyJS

def uglifyjs(src, target)
  begin
    require 'uglifier'
  rescue LoadError => e
    if verbose
      puts "\nYou'll need the 'uglifier' gem for minification. Just run:\n\n"
      puts "  $ gem install uglifier"
      puts "\nand you should be all set.\n\n"
      exit
    end
    return false
  end
  puts "Minifying #{File.basename(src)} with UglifyJS...".pink
  File.open(target, "w"){|f| f.puts Uglifier.new.compile(File.read(src))}
  puts "* Minified into '#{File.basename(target)}'".yellow
end


# Custom monkey patching for String class in order 
# to support colorized output

class String
  # colorization
  def colorize(color_code)
    "\e[#{color_code}m#{self}\e[0m"
  end

  def red
    colorize(31)
  end

  def green
    colorize(32)
  end

  def yellow
    colorize(33)
  end

  def pink
    colorize(35)
  end
end

# Custom monkey patching in order to get File to support a prepend 
# operation to the beginning of the file.

class File
  def self.prepend(path, string)
    Tempfile.open File.basename(path) do |tempfile|
      # prepend data to tempfile
      tempfile << string

      File.open(path, 'r+') do |file|
        # append original data to tempfile
        tempfile << file.read
        # reset file positions
        file.pos = tempfile.pos = 0
        # copy all data back to original file
        file << tempfile.read
      end
    end
  end
end
