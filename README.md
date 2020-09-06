# kubernetes 구성

## 1. 비공개 클러스터 생성 배포 환경 다운로드

{% embed url="https://cloud.google.com/kubernetes-engine/docs/how-to/private-clusters?hl=ko\#before\_you\_begin" %}

```yaml
git clone https://github.com/hyunsukgo/ttc-team-03.git
```

## 2.  WAS 환경 구성하기

### 2-0 namespace 생성

```bash
kubectl create ns ttc-team-03
```

### 2-1 configmap 설정

```yaml
cd ttc-team-03/wordpress
kubectl apply -f 1.configmap.yaml
```

### 2-2 storage 설정

```yaml
kubectl apply -f 2.storage.yaml
```

{% code title="2.storage.yaml" %}
```yaml
kind: StorageClass
apiVersion: storage.k8s.io/v1
metadata:
  name: wordpress
  namespace: ttc-team-03
  labels:
    service: wordpress
provisioner: kubernetes.io/gce-pd
parameters:
  type: pd-ssd
```
{% endcode %}

### 2-3 statefulset 설정

```yaml
kubectl apply -f 3.wordpress-statefulset.yaml
```

{% code title="3.wordpress-statefulset.yaml" %}
```yaml
apiVersion: apps/v1
kind: StatefulSet
metadata:
  name: wordpress
  namespace: ttc-team-03
  labels:
    app: wordpress
spec:
  serviceName: wordpress
  replicas: 3
  selector:
    matchLabels:
      app: wordpress
  template:
    metadata:
      labels:
        app: wordpress
    spec:
      containers:
        - image: gcr.io/ttc-team-03/wordpress:test
          name: wordpress
          resources:
            requests:
              memory: 1Gi
              cpu: 100m
            limits: 
              memory: 2Gi
              cpu: 200m
          env:
            - name: WORDPRESS_DB_INFO
              valueFrom:
                configMapKeyRef:
                  name: wordpress-configuration
                  key: WORDPRESS_DB_HOST
            - name: WORDPRESS_DB_INFO
              valueFrom:
                configMapKeyRef:
                   name: wordpress-configuration
                   key: WORDPRESS_DB_NAME
            - name: WORDPRESS_DB_INFO
              valueFrom:
                configMapKeyRef:
                   name: wordpress-configuration
                   key: WORDPRESS_DB_NAME
            - name: WORDPRESS_DB_INFO
              valueFrom:
                configMapKeyRef:
                  name: wordpress-configuration
                  key: WORDPRESS_DB_USER
            - name: WORDPRESS_DB_INFO
              valueFrom:
                configMapKeyRef:
                   name: wordpress-configuration
                   key: WORDPRESS_DB_PASSWORD
            - name: WORDPRESS_DB_INFO
              valueFrom:
                configMapKeyRef:
                   name: wordpress-configuration
                   key: _TITLE_
            - name: WORDPRESS_DB_INFO
              valueFrom:
                configMapKeyRef:
                   name: wordpress-configuration
                   key: _NAME_
            - name: WORDPRESS_DB_INFO
              valueFrom:
                configMapKeyRef:
                   name: wordpress-configuration
                   key: _PASS_
            - name: WORDPRESS_DB_INFO
              valueFrom:
                configMapKeyRef:
                   name: wordpress-configuration
                   key: _EMAIL_
          ports:
            - containerPort: 80
              name: wordpress
          volumeMounts:
            - name: data
              mountPath: /var/www/html
  volumeClaimTemplates:
  - metadata:
      name: data
    spec:
      accessModes:
        - ReadWriteOnce
      storageClassName: wordpress
      # NOTE: You can increase the storage size
      resources:
        requests:
          storage: 10Gi
```
{% endcode %}

### 2-4 Service 설정 

```yaml
kubectl apply -f 4.service.yaml
```

{% code title="4.service.yaml" %}
```yaml
apiVersion: v1
kind: Service
metadata:
  name: wordpress-service
  namespace: ttc-team-03
  annotations:
    cloud.google.com/neg: '{"ingress": true}'
  labels:
    service: wordpress
spec:
  type: NodePort
  ports:
  - protocol: TCP
    port: 80
    targetPort: 80
  selector:
    app: wordpress
```
{% endcode %}

### 2-5 인증서 추가

```yaml
kubectl apply -f 5.certification.yaml
```

{% code title="5.certification.yaml" %}
```yaml
apiVersion: networking.gke.io/v1beta2
kind: ManagedCertificate
metadata:
  name: wordpress-cert
  namespace: ttc-team-03
spec:
  domains:
    - team03.sk-ttc.com
```
{% endcode %}

### 2-6 Ingress 추가

```yaml
kubectl apply -f 6.ingress-controller.yaml
```

{% code title="6.ingress-controller.yaml" %}
```yaml
apiVersion: extensions/v1beta1
kind: Ingress
metadata:
  name: wordpress-ingress
  namespace: ttc-team-03
  annotations:
    kubernetes.io/ingress.global-static-ip-name: web-static-ip
    networking.gke.io/managed-certificates: wordpress-cert
    #ingress.kubernetes.io/affinity: 'cookie'
    #cloud.google.com/neg: '{"ingress": false}'
  labels:
    app: wordpress
spec:
    backend:
      serviceName: wordpress-service
      servicePort: 80
```
{% endcode %}

## 3. Elastic Search 구성

statefulset으로 구성하였으며, 서비스 연결을 위하여 Cluster IP를 추가  IP는 Service IP Range를 고려하여 설

```yaml
cd ../elasticsearch
```

### 3-1 Storage Class 구성

```yaml
kubectl apply -f 1.storage.yaml
```

{% code title="1.storage.yaml" %}
```yaml
kind: StorageClass
apiVersion: storage.k8s.io/v1
metadata:
  name: ssd
  namespace: ttc-team-03
  labels:
    service: elasticsearch
provisioner: kubernetes.io/gce-pd
parameters:
  type: pd-ssd
```
{% endcode %}

### 3-2 Elastic Search 구성

```yaml
kubectl apply -f 2.es.yaml
```

{% code title="2.es.yaml" %}
```yaml
apiVersion: apps/v1
kind: StatefulSet
metadata:
  name: elasticsearch
  namespace: ttc-team-03
  labels:
    service: elasticsearch
spec:
  serviceName: es
  # NOTE: This is number of nodes that we want to run
  # you may update this
  replicas: 3
  selector:
    matchLabels:
      service: elasticsearch
  template:
    metadata:
      labels:
        service: elasticsearch
    spec:
      terminationGracePeriodSeconds: 300
      initContainers:
      # NOTE:
      # This is to fix the permission on the volume
      # By default elasticsearch container is not run as
      # non root user.
      # https://www.elastic.co/guide/en/elasticsearch/reference/current/docker.html#_notes_for_production_use_and_defaults
      - name: fix-the-volume-permission
        image: busybox
        command:
        - sh
        - -c
        - chown -R 1000:1000 /usr/share/elasticsearch/data
        securityContext:
          privileged: true
        volumeMounts:
        - name: data
          mountPath: /usr/share/elasticsearch/data
      # NOTE:
      # To increase the default vm.max_map_count to 262144
      # https://www.elastic.co/guide/en/elasticsearch/reference/current/docker.html#docker-cli-run-prod-mode
      - name: increase-the-vm-max-map-count
        image: busybox
        command:
        - sysctl
        - -w
        - vm.max_map_count=262144
        securityContext:
          privileged: true
      # To increase the ulimit
      # https://www.elastic.co/guide/en/elasticsearch/reference/current/docker.html#_notes_for_production_use_and_defaults
      - name: increase-the-ulimit
        image: busybox
        command:
        - sh
        - -c
        - ulimit -n 65536
        securityContext:
          privileged: true
      containers:
      - name: elasticsearch
        image: docker.elastic.co/elasticsearch/elasticsearch-oss:6.2.4
        
        ports:
        - containerPort: 9200
          name: http
        - containerPort: 9300
          name: tcp
        # NOTE: you can increase this resources
        resources:
          requests:
            memory: 2Gi
            cpu: 10m
          limits:
            memory: 4Gi
            cpu: 20m
        env:
          # NOTE: the cluster name; update this
          - name: cluster.name
            value: elasticsearch-cluster
          - name: node.name
            valueFrom:
              fieldRef:
                fieldPath: metadata.name
          # NOTE: This will tell the elasticsearch node where to connect to other nodes to form a cluster
          - name: discovery.zen.ping.unicast.hosts
            value: "elasticsearch-0.es.default.svc.cluster.local,elasticsearch-1.es.default.svc.cluster.local,elasticsearch-2.es.default.svc.cluster.local,elasticsearch-3.es.default.svc.cluster.local,elasticsearch-4.es.default.svc.cluster.local"
          # NOTE: You can increase the heap size
        volumeMounts:
        - name: data
          mountPath: /usr/share/elasticsearch/data
  volumeClaimTemplates:
  - metadata:
      name: data
    spec:
      accessModes:
        - ReadWriteOnce
      storageClassName: ssd
      # NOTE: You can increase the storage size
      resources:
        requests:
          storage: 10Gi
```
{% endcode %}

### 3-3 Service 생성

```yaml
kubectl apply -f service.yaml
```

{% code title="service.yaml" %}
```yaml
apiVersion: v1
kind: Service
metadata:
  name: es
  namespace: ttc-team-03
  labels:
    service: elasticsearch
spec:
  clusterIP: 10.20.10.100
  ports:
  - port: 9200
    name: serving
  - port: 9300
    name: node-to-node
  selector:
    service: elasticsearch
```
{% endcode %}

## 4. HPA, Network Policy 생성

```yaml
cd ../mgmt/
```

### 4-1. WAS 확장을 위한 HPA

```yaml
kubectl apply -f 1.wordpress-hpa.yaml
```

{% code title="1.wordpress-hpa.yaml" %}
```yaml
apiVersion: autoscaling/v2beta1
kind: HorizontalPodAutoscaler
metadata:
  name: wordpress-hpa
  namespace: ttc-team-03
spec:
  scaleTargetRef:
    apiVersion: apps/v1
    kind: StatefulSet
    name: wordpress
  minReplicas: 3
  maxReplicas: 9
  metrics:
  - type: Resource
    resource:
      name: cpu
      targetAverageUtilization: 70
  - type: Resource
    resource:
      name: memory 
      targetAverageUtilization: 70

```
{% endcode %}

### 4-2 Elastic Search를 위한 HPA

```yaml
kubectl apply -f 2.elasticsearch-hpa.yaml
```

{% code title="2.elasticsearch-hpa.yaml" %}
```yaml
apiVersion: autoscaling/v2beta1
kind: HorizontalPodAutoscaler
metadata:
  name: elasticsearch-hpa
  namespace: ttc-team-03
spec:
  scaleTargetRef:
    apiVersion: apps/v1
    kind: StatefulSet
    name: elasticsearch
  minReplicas: 3
  maxReplicas: 6
  metrics:
  - type: Resource
    resource:
      name: cpu
      targetAverageUtilization: 70
  - type: Resource
    resource:
      name: memory 
      targetAverageUtilization: 70
```
{% endcode %}

### 4-3  Network Policy 적용

Network Policy를 적용하기 위해서는 클러스터 설정에서 네트워크 정책이 활성화 되어 있어야 합니다.

![](.gitbook/assets/image%20%282%29.png)

```yaml
kubectl apply -f 3.networkpolicy.yaml
```

{% code title="kubectl apply -f 3.networkpolicy.yaml" %}
```yaml
apiVersion: networking.k8s.io/v1
kind: NetworkPolicy
metadata:
  name: wordpress-network-policy
  namespace: ttc-team-03
spec:
  podSelector:
    matchLabels:
      app: wordpress
  policyTypes:
  - Ingress
  ingress:
  - from:
    - ipBlock:
        cidr: 0.0.0.0/0
        except:
        - 172.16.0.0/28 # Master Ip Range
        - 218.38.51.93/32 #My IP
        - 35.191.0.0/16 # Health Check
        - 209.85.152.0/22 # Health Check
        - 209.85.204.0/22 # Health Check
        - 130.211.0.0/22 # Health Check
        - 3.34.33.23/32 # SK C&C IP Range
        - 211.45.60.5/31 # SK C&C IP Range
        - 10.16.0.0/14 # Pod IP Range
        - 10.20.0.0/20 # Service IP Range
        - 10.100.100.0/24 #Node IP Range
    ports:
    - protocol: TCP
      port: 80
    - protocol: TCP
      port: 443
    - protocol: TCP
      port: 10256
    - protocol: TCP
      port: 32573
  egress:
    - {}
```
{% endcode %}



