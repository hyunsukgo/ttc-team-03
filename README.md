# ttc-team-03



### 2-3 statefulset 설정

### 2-3 statefulset 설

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



