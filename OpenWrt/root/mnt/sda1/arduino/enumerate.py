import os
 
path="pics"  
dirList=os.listdir(path)
orderedDirList=sorted(dirList)
i=0
for fname in orderedDirList:
    previous_name = path + "/" + fname
    new_name = path + "/%03d.jpg" % i
 
    os.rename(previous_name,new_name) 
    i+=1


#
#./mjpg_streamer -i "./input_uvc.so -f 10 -r 640x480" -o "./output_http.so -w ./www" -o "./output_file.so -f pics -d 120000"
#will create files like this:
#2010_03_07_18_34_11_picture_000000000.jpg
#2010_03_07_18_34_11_picture_000000001.jpg
#2010_03_07_18_34_11_picture_000000002.jpg
#...
#python enumerate.py
#opkg update
#opkg install ffmpeg
#ffmpeg -r 15 -b 200k -i pics/%03d.jpg mymovie.mp4
#Now we have time laps movie!!
