import pickle, sys, time

start_time = time.time()

x1, x2, x3, x4, id = float(sys.argv[1]), float(sys.argv[2]), float(sys.argv[3]), float(sys.argv[4]), sys.argv[5]

loaded_model = pickle.load(open('kmeansmodel.pickle', 'rb'))
result = loaded_model.predict([[x1, x2, x3, x4]])

# print("Artist ID: {} | Final Score: {} | Cluster: {} | Time: {}".format(id, x4, result, (time.time() - start_time)))

print(str(result)[1])

"""
import pickle

loaded_model = pickle.load(open('kmeansmodel.pickle', 'rb'))
result = loaded_model.predict([[0.866667, 0.8, 0.866667, 86.666667]])

print("Cluster:", result)


"""



"""

import threading
import pickle

def predict2(n):
    # from sklearn.cluster import KMeans
    

    loaded_model = pickle.load(open('kmeansmodel.pickle', 'rb'))
    result = loaded_model.predict([n])

    print(result)


scores = [0.866667, 0.8, 0.866667, 86.666667]
threading.Thread(target = predict2, args = (scores,)).start()
"""