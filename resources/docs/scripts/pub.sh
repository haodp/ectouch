

for i in `git tag -l`;
do
    version="ECTouch_${i}_SC_UTF8.zip"
    # resource
    if [ ! -f ${resource_path}/${i}/${version} ];then
        git archive --format=zip --output=${resource_path}/${i}/${version} ${i}
        echo archive resource ${i} done \! ;
    else
        echo archive resource ${i} skip \! ;
    fi
done