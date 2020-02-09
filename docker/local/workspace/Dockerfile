FROM laradock/workspace:2.2-7.2

RUN apt-get update && \
    apt-get install -y openssh-server mysql-client nmap

COPY run.sh /usr/local/bin/run.sh
RUN chmod +x /usr/local/bin/run.sh

CMD ["/usr/local/bin/run.sh"]
