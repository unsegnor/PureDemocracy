FROM ubuntu:latest

# Add crontab file in the cron directory
ADD check-votes /etc/cron.d/check-votes

# Give execution rights on the cron job
RUN chmod 0644 /etc/cron.d/check-votes

# Create the log file to be able to run tail
RUN touch /var/log/cron.log

#Install Cron
RUN apt-get update
RUN apt-get -y install cron curl

# Run the command on container startup
CMD cron && tail -f /var/log/cron.log