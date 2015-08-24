role :app, %w{legendik@dwarfsearch.com}

server 'dwarfsearch.com', user: 'legendik', roles: %w{app}, domain: "www.dwarfsearch.com"

set :deploy_to, '/home/projects/dwarfsearch.com/web/'

set :ssh_options, {
  forward_agent: true
}
