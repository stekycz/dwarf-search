# config valid only for current version of Capistrano
lock '3.4.0'

set :application, 'dwarfsearch.com'
set :repo_url, 'git@github.com:legendik/dwarf-search.git'

ask :branch, proc { `git rev-parse --abbrev-ref HEAD`.chomp }.call

set :log_level, :info

set :linked_files, %w{app/config/config.local.neon}

set :default_env, { path: "$PATH:$HOME/bin" }

set :format, :pretty

set :keep_releases, 5

namespace :load do
  task :defaults do
    set :cache_redis_db, nil
    set :curl_params, ""
    set :current_redis_db, nil
  end
end

namespace :cache do

  task :flush_old_redis do
    on release_roles(:app) do
      execute "redis-cli", "flushall"
    end
  end
end

namespace :cleanup do
  task :remove_dirs do
    on release_roles(:app) do
      within release_path do
        execute "rm", "-rf ./.capistrano ./app/sql ./config ./tests"
        execute "rm", "-f ./README.md"
      end
    end
  end

  task :clear_logs do
     on release_roles(:app) do
       within release_path do
         execute "rm", "-f ./log/email-sent"
         date = capture("date", "+%Y-%m-%d.%H:%M:%S")
         log_dir = "./log/logs.#{date}"
         execute "mkdir", log_dir
         execute "find", "./log/ -maxdepth 1 -type f \\( ! -iname \".*\" \\) -exec mv {} #{log_dir} \\;";
       end
     end
   end
end

namespace :compile do
  task :doctrine_proxies do
    on release_roles(:app) do
      within release_path do
        execute "php", "./www/index.php orm:generate:proxies"
      end
    end
  end
end

namespace :bower do
    task :install do
      on release_roles(:app) do
        within release_path do
          execute "bower", "install"
        end
      end
    end
end

namespace :deploy do
  before :updated, :"cleanup:remove_dirs"

  after :updated, :"bower:install"
  after :updated, :"compile:doctrine_proxies"
  after :updated, :"cleanup:clear_logs"

  before :reverted, :"cleanup:remove_dirs"

  after :reverted, :"cleanup:clear_logs"

  # publish
  after :published, :"cache:flush_old_redis"
end

after 'composer:install', 'bower:install'
